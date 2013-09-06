<?php
class MLCGitRepo{
    const SSH_BASE_URL = 'git@github.com:';
    const HTTPS_BASE_URL = 'https://github.com/';
    const PUBLIC_BASE_URL = 'git://github.com/';
    const EXTENSION = '.git';
	protected $strDir = null;
	protected $strRepoNamespace = null;
	public function __construct($strDir, $strRepoUrl = null){
		$this->strDir = $strDir;
		if(is_null($strRepoUrl)){
			$strData = $this->Remote(
				'',
				array(
					'-v'=>''
				)
			);
			$arrParts = explode("\n", $strData);
			if(
				(count($arrParts) > 0) &&
				(substr($arrParts[0],0, 6) == 'origin')
			){
				$this->strRepoUrl = trim(substr($arrParts[0],6));
			}
		}else{

            $strRepoNamespace = self::UrlToNamespace($strRepoUrl);
            $this->strRepoNamespace = $strRepoNamespace;
        }
	}
	public function Remote($strBody, $arrParams = array()){
		return $this->_Exicute('remote', $arrParams, $strBody);
	}
	public function Init(){
		return $this->_Exicute('init', array(), $this->strDir);
	}
	public function GClone(){
		return $this->_Exicute('clone', array(), $this->GetRepoUrl(),  $this->strDir, true);
	}
	public function Add($strFileLoc){
		/*switch(substr($strFileLoc, 0, 1)){
			case('/'):
				
			break;
			case('./'):
				$strFileLoc = $this->strDir . substr($strFileLoc, 2);
			break;
			default:
				$strFileLoc = $this->strDir . '/' .$strFileLoc;
			break;
		}*/
		return $this->_Exicute('add', array(), $strFileLoc);
	}
	public function Commit($arrParams = array()){
        exec(' cd ' . $this->strDir . ';
            git config push.default matching;
            git config user.email "mlea@schematical.com";
            git config user.name "Schematical Robot";
        ');
		if(is_string($arrParams)){
			$strMessage = $arrParams;
			$arrParams = array();
			$arrParams['-m'] = $strMessage;
		}
		return $this->_Exicute('commit', $arrParams, $this->strDir);
	}
	public function Push($arrParams = array()){
		return $this->_Exicute('push', $arrParams, null, null, true);
	}
	protected function _Exicute($strCommand, $arrFlags, $strParam1, $strParam2 = null, $blnAddSShSupport = false){


		$strFlags = '';
        $strBody = '';
        if(!is_null($strParam1)){
            $strBody .= escapeshellarg($strParam1);
        }
        if(!is_null($strParam2)){
            $strBody .= ' ' . escapeshellarg($strParam2);
        }
		foreach($arrFlags as $strKey => $strValue){
            $strFlags .= $strKey . ' ';
            if(strlen($strValue) > 0){
                $strFlags .=   escapeshellarg($strValue) . ' ';
            }
		}
		$strShell = sprintf(
			'git %s %s %s', 
			$strCommand, 
			$strFlags, 
			$strBody
		);
        //Hack to fix crap
        $strShell = ' cd ' . $this->strDir . '; ' . $strShell;
        if($blnAddSShSupport){
            $strShell = "ssh-agent bash -c \"ssh-add ~/.ssh/schematical; " . $strShell ."\"";
        }
        //error_log($strShell);
		$strOutput = exec($strShell, $arrOutput);
        error_log($strShell . ' ----- ' . $strOutput);
		return $strOutput;
	}
    public function GetRepoNamespace(){
        return $this->strRepoNamespace;
    }
    public function GetRepoUrl(){
        return self::SSH_BASE_URL . $this->strRepoNamespace . self::EXTENSION;
    }
    public static function UrlToNamespace($strRepoUrl){

        if(strpos($strRepoUrl, '../') !== false){
            throw new Exception("Tricky Tricky");
        }
        $strRepoBase = substr($strRepoUrl, 0, strlen(MLCGitRepo::HTTPS_BASE_URL));
        $strSSHRepoBase = substr($strRepoUrl, 0, strlen(MLCGitRepo::SSH_BASE_URL));
        if($strRepoBase == MLCGitRepo::HTTPS_BASE_URL){
            $strRepoNamespace = substr($strRepoUrl, strlen(MLCGitRepo::HTTPS_BASE_URL));
        }elseif($strSSHRepoBase == MLCGitRepo::SSH_BASE_URL){//TODO: WRITE SSH STUFF
            $strRepoNamespace = substr($strRepoUrl, strlen(MLCGitRepo::SSH_BASE_URL));
        }else{
            $strRepoNamespace = $strRepoUrl;
        }

        if(
            substr(
                $strRepoNamespace,
                strlen($strRepoNamespace) - strlen(self::EXTENSION),
                strlen(self::EXTENSION)
            ) == self::EXTENSION
        ){
            $strRepoNamespace = substr(
                $strRepoNamespace,
                0,
                strlen($strRepoNamespace) - strlen(self::EXTENSION)
            );
        }

        if(substr($strRepoNamespace, 0, 1) == '/'){
            $strRepoNamespace = substr($strRepoNamespace, 1);
        }


        return $strRepoNamespace;
    }
	
}
