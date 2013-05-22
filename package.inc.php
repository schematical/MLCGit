<?php
define('__MLC_GIT__', dirname(__FILE__));
define('__MLC_GIT_CORE__', __MLC_GIT__ . '/_core');

define('__MLC_GIT_CORE_CTL__', __MLC_GIT_CORE__ . '/ctl');
define('__MLC_GIT_CORE_VIEW__', __MLC_GIT_CORE__ . '/view');
define('__MLC_GIT_BATCH__', __MLC_GIT_CORE__ . '/batch');
define('__MLC_GIT_CG__', __MLC_GIT__ . '/_codegen');
MLCApplicationBase::$arrClassFiles['MLCGitClient'] = __MLC_GIT_CORE__ . '/MLCGitClient.class.php';
MLCApplicationBase::$arrClassFiles['MLCGitRepo'] = __MLC_GIT_CORE__ . '/MLCGitRepo.class.php';
//Ctl

MLCApplicationBase::$arrClassFiles['MLCGitAuthPanel'] = __MLC_GIT_CORE_CTL__ . '/MLCGitAuthPanel.class.php';

require_once(__MLC_GIT_CORE__ . '/_enum.inc.php');

//MSTDriver::Init();
