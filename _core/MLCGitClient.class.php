<?php
class MLCGitClient{
	const APP_ID = 'app_id';
	const APP_SECRET = 'app_secret';
	protected static $objClient = null;
	protected $strUrlBase = 'https://github.com';
	protected $strClientId = null;
	protected $strClientSecret = null;
	public static function Init(){
		if(is_null(self::$objClient)){
			self::$objClient = new MLCGitClient(__GIT_API_KEY__, __GIT_API_SECRET__);
		}
		return self::$objClient;
	}
	public function __construct($strClientId, $strClientSecret){
		$this->strClientId = $strClientId;
		$this->strClientSecret = $strClientSecret;
	}
	public function GetAuthorizeUrl($mixData = array()){
		$strUrl = $this->strUrlBase . MLCGitLogin::Endpoint;
		if(is_string($mixData)){
			$arrData = array(
				MLCGitLogin::redirect_uri => $mixData
			);
		}else{
			$arrData = $mixData;
		}
		$arrData[MLCGitLogin::client_id] = $this->strClientId;
		$strUrl .= '?' . http_build_query($arrData);
		return $strUrl;
	}
	protected function _send($strUrl, $arrParams = array(), $blnPost = false){
		$arrHeaders = array(
			'Accept: application/json',
			'Content-Type: application/json',
			self::APP_ID . ':' . $this->_appId,
			self::APP_SECRET . ':' . $this->_appSecret
		);
		if(!$blnPost){
			if(strpos($strUrl, '?') === false){
				$strUrl .= '?';
			}else{
				$strUrl .= '&';
			}
			$strUrl .= http_build_query($arrParams);
		}
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $strUrl);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		if($blnPost){
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($arrParams));
		} 
		curl_setopt($ch, CURLOPT_HTTPHEADER, $arrHeaders);
		//curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
		//curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		//curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__) . '/Certificate/cacert.pem');
		
		$strReturn = curl_exec($ch);
		$curlError = curl_error($ch);
		$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

		if (intval($httpCode / 100) != 2) {
			if ($httpCode == 422) {
				$strReturn = json_decode($strReturn);
				throw new Exception($strReturn->Message, $strReturn->ErrorCode);
			} else {
				throw new Exception("Error while calling API. Api returned HTTP code {$httpCode} with message \"{$strReturn}\"", $httpCode);
			}
		}
		$arrReturn = json_decode($strReturn, true);
		if(!is_array($arrReturn)){
			die($strReturn);
		}else{
			return $arrReturn;
		}
	}
	
}
