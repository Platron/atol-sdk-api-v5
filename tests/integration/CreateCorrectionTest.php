<?php

namespace Platron\AtolV5\tests\integration;

use Platron\AtolV5\clients\PostClient;
use Platron\AtolV5\data_objects\AgentInfo;
use Platron\AtolV5\data_objects\Company;
use Platron\AtolV5\data_objects\Correction;
use Platron\AtolV5\data_objects\CorrectionInfo;
use Platron\AtolV5\data_objects\Payment;
use Platron\AtolV5\data_objects\Supplier;
use Platron\AtolV5\data_objects\Vat;
use Platron\AtolV5\data_objects\PayingAgent;
use Platron\AtolV5\data_objects\ReceivePaymentsOperator;
use Platron\AtolV5\data_objects\MoneyTransferOperator;
use Platron\AtolV5\handbooks\CorrectionOperationTypes;
use Platron\AtolV5\handbooks\CorrectionTypes;
use Platron\AtolV5\handbooks\PaymentTypes;
use Platron\AtolV5\handbooks\PaymentMethods;
use Platron\AtolV5\handbooks\PaymentObjects;
use Platron\AtolV5\handbooks\SnoTypes;
use Platron\AtolV5\handbooks\Vates;
use Platron\AtolV5\handbooks\AgentTypes;
use Platron\AtolV5\handbooks\ReceiptOperationTypes;
use Platron\AtolV5\SdkException;
use Platron\AtolV5\services\CreateCorrectionRequest;
use Platron\AtolV5\services\CreateReceiptResponse;
use Platron\AtolV5\services\GetTokenResponse;
use Platron\AtolV5\services\GetStatusResponse;
use Platron\AtolV5\services\GetStatusRequest;
use Platron\AtolV5\services\GetTokenRequest;

use Platron\AtolV5\data_objects\Item;
use Platron\AtolV5\data_objects\MarkQuantity;
use Platron\AtolV5\data_objects\MarkCode;
use Platron\AtolV5\handbooks\MarkCodeTypes;
use Platron\AtolV5\data_objects\SectoralBase;
use Platron\AtolV5\data_objects\SectoralItemProps;
class CreateCorrectionTest extends IntegrationTestBase
{
	public function testCreateCorrection()
	{
		$client = new PostClient();
		$client->addLogger(new TestLogger());

		$tokenService = $this->createTokenRequest();
		$tokenResponse = new GetTokenResponse($client->sendRequest($tokenService));

		$this->assertTrue($tokenResponse->isValid());

		$createReceiptRequest = $this->createCorrectionRequest($tokenResponse->token);
		$createReceiptResponse = new CreateReceiptResponse($client->sendRequest($createReceiptRequest));

		$this->assertTrue($createReceiptResponse->isValid());

		$getStatusRequest = $this->createGetStatusRequest($createReceiptResponse, $tokenResponse);

		if(!$this->checkCorrectionStatus($client, $getStatusRequest)){
			$this->fail('Correction don`t change status');
		}
	}

	/**
	 * @param string $token
	 * @return CreateCorrectionRequest
	 */
	private function createCorrectionRequest($token)
	{
		$correction = $this->createCorrection();
		$externalId = time();
		$createCorrectionRequest = new CreateCorrectionRequest($token, $this->groupCode, $externalId, $correction);
		$createCorrectionRequest->setDemoMode();
		return $createCorrectionRequest;
	}

	/**
	 * @return GetTokenRequest
	 */
	private function createTokenRequest()
	{
		$tokenRequest = new GetTokenRequest($this->login, $this->password);
		$tokenRequest->setDemoMode();
		return $tokenRequest;
	}

	/**
	 * @param $createReceiptResponse
	 * @param $tokenResponse
	 * @return GetStatusRequest
	 */
	private function createGetStatusRequest($createReceiptResponse, $tokenResponse)
	{
		$getStatusRequest = new GetStatusRequest($this->groupCode, $createReceiptResponse->uuid, $tokenResponse->token);
		$getStatusRequest->setDemoMode();
		return $getStatusRequest;
	}

	/**
	 * @return Company
	 */
	private function createCompany()
	{
		$company = new Company(
			'test@test.ru',
			new SnoTypes(SnoTypes::ESN),
			$this->inn,
			$this->paymentAddress
		);
		return $company;
	}

	/**
	 * @return CorrectionInfo
	 */
	private function createCorrectionInfo()
	{
		$correctionInfo = new CorrectionInfo(
			new CorrectionTypes(CorrectionTypes::SELF),
			new \DateTime(),
			'Test base number'
		);
		return $correctionInfo;
	}

	/**
	 * @return Payment
	 */
	private function createPayment()
	{
		$payment = new Payment(
			new PaymentTypes(PaymentTypes::ELECTRON),
			100
		);
		return $payment;
	}

	/**
	 * @return Vat
	 */
	private function createVat()
	{
		$vat = new Vat(new Vates(Vates::VAT10));
		$vat->addSum(100);
		return $vat;
	}

	/**
	 * @return Item
	 */
	private function createItem()
	{
		$vat = $this->createVat();
		$item = new Item(
			'Test Product',
			100,
			1,
			$vat
		);
		$agentInfo = $this->createAgentInfo();
		$item->addAgentInfo($agentInfo);
		$item->getPositionSum(100);
		$item->addMeasure(0);
		$item->addMarkProcessingMode(0);
		$markQuantity = $this->createMarkQuantity();
		$item->addMarkQuantity($markQuantity);
		$code ="MDEwNDYwNzQyODY3OTA5MDIxNmVKSWpvV0g1NERkVSA5MWZmZDAgOTJzejZrU1BpckFwZk1CZnR2TGJvRTFkbFdDLzU4aEV4UVVxdjdCQmtabWs0PQ==";
		$markCode = new MarkCode(
			new MarkCodeTypes(
			MarkCodeTypes::GS1M),
			$code);
		$item->addMarkCode($markCode);
		$sectoral_item_props = $this->createSectoralItemProps();
		$item->addSectoralItemProps($sectoral_item_props->getParameters());
		$item->addPaymentMethod(new PaymentMethods(PaymentMethods::FULL_PAYMENT));
		$item->addPaymentObject(new PaymentObjects(PaymentObjects::EXCISE_WITH_MARK));
		$item->addUserData('Test user data');
		$item->addExcise(5.64);
		$item->addCountryCode("643");
		$item->addDeclarationNumber("10702020/060520/0013422");
		return $item;
	}
	/**
	 * @return SectoralItemProps
	 */
	private function createSectoralItemProps() {
		$sectoral_item_props = new SectoralItemProps("003");
		$sectoral_item_props->addDate("12.05.2020");
		$sectoral_item_props->addNumber("123/43");
		$sectoral_item_props->addValue("id1=val1&id2=val2&id3=val3");
		return $sectoral_item_props;
	}
	/**
	 * @return AgentInfo
	 */
	private function createAgentInfo()
	{
		$supplier = $this->createSupplier();
		$agentInfo = new AgentInfo(
			new AgentTypes(AgentTypes::PAYING_AGENT),
			$supplier
		);
		$payingAgent = $this->createPayingAgent();
		$agentInfo->addPayingAgent($payingAgent);
		$moneyTransferOperator = $this->createMoneyTransferOperator();
		$receivePaymentOperator = $this->createReceivePaymentOperator();
		$agentInfo->addMoneyTransferOperator($moneyTransferOperator);
		$agentInfo->addReceivePaymentsOperator($receivePaymentOperator);
		return $agentInfo;
	}
	/**
	 * @return PayingAgent
	 */
	private function createPayingAgent()
	{
		$payingAgent = new PayingAgent('Operation name');
		$payingAgent->addPhone('79050000003');
		$payingAgent->addPhone('79050000004');
		return $payingAgent;
	}

	/**
	 * @return MarkQuantity
	 */
	private function createMarkQuantity()
	{
		$markQuantity = new MarkQuantity();
		$markQuantity->addNumerator(4);
		$markQuantity->addDenominator(7);
		return $markQuantity;
	}
	/**
	 * @return Supplier
	 */
	private function createSupplier()
	{
		$supplier = new Supplier('Supplier name');
		$supplier->addInn($this->inn);
		$supplier->addPhone('79050000001');
		$supplier->addPhone('79050000002');
		return $supplier;
	}
	/**
	 * @return MoneyTransferOperator
	 */
	private function createMoneyTransferOperator()
	{
		$moneyTransferOperator = new MoneyTransferOperator('Test moneyTransfer operator');
		$moneyTransferOperator->addInn($this->inn);
		$moneyTransferOperator->addPhone('79050000005');
		$moneyTransferOperator->addAddress('site.ru');
		return $moneyTransferOperator;
	}
	/**
	 * @return ReceivePaymentsOperator
	 */
	private function createReceivePaymentOperator()
	{
		$receivePaymentOperator = new ReceivePaymentsOperator('79050000006');
		$receivePaymentOperator->addPhone('79050000007');
		return $receivePaymentOperator;
	}
	/**
	 * @return Correction
	 */
	private function createCorrection()
	{
		$company = $this->createCompany();
		$correctionInfo = $this->createCorrectionInfo();
		$payment = $this->createPayment();
		$vat = $this->createVat();
		$item = $this->createItem();
		$correction = new Correction(
			new CorrectionOperationTypes(CorrectionOperationTypes::BUY_CORRECTION),
			$company,
			$correctionInfo,
			$payment,
			$vat,
			[$item]
		);
		return $correction;
	}
	/**
	 * @param PostClient $client
	 * @param GetStatusRequest $getStatusRequest
	 * @return bool
	 * @throws SdkException
	 */
	private function checkCorrectionStatus(PostClient $client, GetStatusRequest $getStatusRequest)
	{
		for ($second = 0; $second <= 20; $second++) {
			$getStatusResponse = new GetStatusResponse($client->sendRequest($getStatusRequest));
			if ($getStatusResponse->isReceiptReady()) {
				$this->assertTrue($getStatusResponse->isValid());
				return true;
			} else {
				$second++;
			}
			sleep(1);
		}
		return false;
	}
}