<?php
/**
 * File: mugglepay.php
 * Functionality: mugglepay -mugglepay支付
 * Author: 黄枫叶
 * Date: 2020-04-19
 */
namespace Pay\mugglepay;
use \Pay\notify;


class mugglepay
{
	private $apiHost="https://api.mugglepay.com/v1/orders";
	private $paymethod ="mugglepay";
	
	//处理请求
	public function pay($payconfig,$params)
	{
		try
		{
			
			$fees = (double)$payconfig['configure4'];//手续费费率  比如 0.05
			if($fees>0.00)
			{
				$price_amount =(double)$params['money'] * (1.00+$fees);// 价格 * （1 + 0.05）
			}
			else
			{
				$price_amount =(double)$params['money'];
			}
			
            $merchant_order_id =$params['orderid'];
            $price_amount = sprintf('%.2f', $price_amount);// 只取小数点后两位
			$title = $params['productname'];
			$key   =$payconfig['app_secret'];
			$callback_url = $params['weburl'] . '/product/notify/?paymethod=' . $this->paymethod;  //支付成功后回调地址
			$cancel_url = $params['weburl']. "/query/auto/{$params['orderid']}.html";  //同步地址
			$success_url = $params['weburl']. "/query/auto/{$params['orderid']}.html";  //同步地址
            $token  = md5($merchant_order_id  . $key);

			$config = [
                'merchant_order_id'=>$merchant_order_id,
                'price_amount'=>$price_amount,
			    'price_currency' => 'CNY',
			    'title' =>$title,
				'callback_url' =>$callback_url,
				'cancel_url'=>$cancel_url,
				'success_url' => $success_url,
				'token' => $token
			];
			
			$header = array();
			$header[] = 'token:'.$payconfig['app_secret'];
			$header[] = 'Content-Type:application/json';

            $createOrderUrl = $this->apiHost;

			$ch = curl_init(); //使用curl请求
            curl_setopt($ch, CURLOPT_URL, $createOrderUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($config));
            $mugglepay_json = curl_exec($ch);
            curl_close($ch);
           
		    $mugglepay_date=json_decode($mugglepay_json,true);
			
			if(is_array($mugglepay_date))
			{
				if($mugglepay_date['status'] !== 201&&$mugglepay_date['status'] !== 200)
				{
					return array('code'=>1002,'msg'=>$mugglepay_date['error_code'],'data'=>'');
				}
				else
				{
					$JumpUrl = $mugglepay_date['payment_url'];
					$closetime = 600;			
					$result = array('type'=>1,'subjump'=>0,'url'=>$JumpUrl,'paymethod'=>$this->paymethod,'payname'=>$payconfig['payname'],'overtime'=>$closetime,'money'=>$price_amount);
					return array('code'=>1,'msg'=>'success','data'=>$result);
				}			
			}else
			{
				return array('code'=>1001,'msg'=>"支付接口请求失败",'data'=>'');
			}
		} 
		catch (\Exception $e) 
		{
			return array('code'=>1000,'msg'=>$e->getMessage(),'data'=>'');
		}
	}
	
	//处理返回
	public function notify($payconfig)
	{
		ini_set("error_reporting","E_ALL & ~E_NOTICE");
		
		$inputString = file_get_contents('php://input', 'r');
		$inputStripped = str_replace(array("\r", "\n", "\t", "\v"), '', $inputString);
		$_POST = json_decode($inputStripped, true); //convert JSON into array


		$key=$payconfig['app_secret'];
		$return_merchant_order_id = $_POST['merchant_order_id'];//商户订单号
        $return_pay_amount = $_POST['pay_amount'];
		$token = $_POST['token'];
		$order_id = $_POST['order_id'];
		$return_pay_currency = $_POST['pay_currency'];

        $temp_token = md5($return_merchant_order_id  . $key);

        if ($temp_token !== $token) { //不合法的数据 KEY密钥为你的密钥
             return 'error|Notify: auth fail';
		}
		if($_POST['status'] === 'PAID')
		{
			$config = array('paymethod' => $this->paymethod, 'tradeid' => $order_id, 'paymoney' => $return_pay_amount, 'orderid'=>$return_merchant_order_id );
            $notify = new \Pay\notify();
            $data = $notify->run($config);
            if ($data['code'] > 1) {
                return 'error|Notify: ' . $data['msg'] ;
			} 
			else 
			{
                return json_encode(['status' => 200]);
            }
		}
		else { //合法的数据
            //业务处理
			return 'error|Notify: status illegal';
        }
	}
	
	private function _curlPost($url,$params){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT,300); //设置超时
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); // https请求 不验证证书和hosts
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
		$result = curl_exec($ch);
		curl_close($ch);
		return $result;	
	}		
}