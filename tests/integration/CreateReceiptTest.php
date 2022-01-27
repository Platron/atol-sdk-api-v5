<?php

namespace Platron\AtolV5\tests\integration;

use Platron\AtolV5\clients\PostClient;
use Platron\AtolV5\data_objects\AgentInfo;
use Platron\AtolV5\data_objects\Client;
use Platron\AtolV5\data_objects\Company;
use Platron\AtolV5\data_objects\Item;
use Platron\AtolV5\data_objects\MoneyTransferOperator;
use Platron\AtolV5\data_objects\PayingAgent;
use Platron\AtolV5\data_objects\Payment;
use Platron\AtolV5\data_objects\Receipt;
use Platron\AtolV5\data_objects\ReceivePaymentsOperator;
use Platron\AtolV5\data_objects\Supplier;
use Platron\AtolV5\data_objects\Vat;
use Platron\AtolV5\handbooks\AgentTypes;
use Platron\AtolV5\handbooks\ReceiptOperationTypes;
use Platron\AtolV5\handbooks\PaymentMethods;
use Platron\AtolV5\handbooks\PaymentObjects;
use Platron\AtolV5\handbooks\PaymentTypes;
use Platron\AtolV5\handbooks\SnoTypes;
use Platron\AtolV5\handbooks\Vates;
use Platron\AtolV5\SdkException;
use Platron\AtolV5\services\CreateReceiptRequest;
use Platron\AtolV5\services\CreateReceiptResponse;
use Platron\AtolV5\services\GetStatusResponse;
use Platron\AtolV5\services\GetTokenResponse;
use Platron\AtolV5\services\GetStatusRequest;
use Platron\AtolV5\services\GetTokenRequest;

use Platron\AtolV5\data_objects\MarkQuantity;
use Platron\AtolV5\data_objects\MarkCode;
use Platron\AtolV5\handbooks\MarkCodeTypes;
use Platron\AtolV5\data_objects\SectoralBase;
use Platron\AtolV5\data_objects\SectoralItemProps;
use Platron\AtolV5\data_objects\SectoralCheckProps;
use Platron\AtolV5\data_objects\OperatingCheckProps;
use Platron\AtolV5\data_objects\AdditionalUserProps;

class CreateReceiptTest extends IntegrationTestBase
{
	public function testCreateReceipt()
	{
		$client = new PostClient();
		$client->addLogger(new TestLogger());

		$tokenService = $this->createTokenRequest();
		$tokenResponse = new GetTokenResponse($client->sendRequest($tokenService));

		$this->assertTrue($tokenResponse->isValid());

		$createReceiptRequest = $this->createReceiptRequest($tokenResponse->token);
		$createReceiptResponse = new CreateReceiptResponse($client->sendRequest($createReceiptRequest));

		$this->assertTrue($createReceiptResponse->isValid());

		$getStatusRequest = $this->createGetStatusRequest($createReceiptResponse, $tokenResponse);
		if(!$this->checkReceiptStatus($client, $getStatusRequest)){
			$this->fail('Receipt don`t change status');
		}
	}

	/**
	 * @return Client
	 */
	private function createCustomer()
	{
		$customer = new Client();
		$customer->addEmail('test@test.ru');
		$customer->addPhone('79050000000');
		$customer->addBirthdate("18.11.1990");
		$customer->addCitizenship("643");
		$customer->addDocumentCode("21");
		$customer->addDocumentData("4507 443564");
		$customer->addAddress("г.Москва, Ленинский проспект д.1 кв 43");
		return $customer;
	}

	/**
	 * @return Company
	 */
	private function createCompany()
	{
		$company = new Company(
			'company@test.ru',
			new SnoTypes(SnoTypes::ESN),
			$this->inn,
			$this->paymentAddress
		);
		return $company;
	}

	/**
	 * @return Vat
	 */
	private function createVat()
	{
		$vat = new Vat(new Vates(Vates::VAT10));
		$vat->addSum(10);
		return $vat;
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
	 * @return Item
	 */
	private function createItem()
	{
		$vat = $this->createVat();
		$item = new Item(
			'Test Product',
			10,
			1,
			$vat
		);
		$agentInfo = $this->createAgentInfo();
		$item->addAgentInfo($agentInfo);
		$item->getPositionSum(10);

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
	 * @return SectoralBase
	 */
	private function createSectoralCheckProps() {
		$sectoral_check_props = new SectoralCheckProps("004");
		$sectoral_check_props->addDate("15.08.2020");
		$sectoral_check_props->addNumber("123/43");
		$sectoral_check_props->addValue("id1=val1&id2=val2&id3=val3");
		return $sectoral_check_props;
	}

	/**
	 * @return OperatingCheckProps
	 */

	private function createOperatingCheckProps()
	{
		$operating_check_props = new OperatingCheckProps("0");
		$operating_check_props->addValue("Operating check props value");
		$operating_check_props->addTimestamp("12.10.2020 17:20:55");
		return $operating_check_props;
	}

	/**
	 * @return AdditionalUserProps
	 */

	private function createAdditionalUserProps()
	{
		$additional_user_props = new AdditionalUserProps();
		$additional_user_props->addName("Additional user props name");
		$additional_user_props->addValue("Additional user props value");
		return $additional_user_props;
	}

	/**
	 * @return Payment
	 */
	private function createPayment()
	{
		$payment = new Payment(
			new PaymentTypes(PaymentTypes::ELECTRON),
			10
		);
		return $payment;
	}

	/**
	 * @return Receipt
	 */
	private function createReceipt()
	{
		$item = $this->createItem();
		$payment = $this->createPayment();
		$customer = $this->createCustomer();
		$company = $this->createCompany();
		$receipt = new Receipt($customer, $company, [$item], $payment, new ReceiptOperationTypes(ReceiptOperationTypes::BUY));

		$sectoral_check_props = $this->createSectoralCheckProps();
		$receipt->addSectoralCheckProps($sectoral_check_props);

		$operating_check_props = $this->createOperatingCheckProps();
		$receipt->addOperatingCheckProps($operating_check_props);

		$additional_user_props = $this->createAdditionalUserProps();
		$receipt->addAdditionalUserProps($additional_user_props);

		return $receipt;
	}

	/**
	 * @param string $token
	 * @return CreateReceiptRequest
	 */
	private function createReceiptRequest($token)
	{
		$receipt = $this->createReceipt();
		$externalId = time();
		$createReceiptRequest = new CreateReceiptRequest(
			$token,
			$this->groupCode,
			$externalId,
			$receipt
		);
		$createReceiptRequest->setDemoMode();
		return $createReceiptRequest;
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
	 * @param PostClient $client
	 * @param GetStatusRequest $getStatusRequest
	 * @return bool
	 * @throws SdkException
	 */
	private function checkReceiptStatus(PostClient $client, GetStatusRequest $getStatusRequest)
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
