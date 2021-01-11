<?php
declare(strict_types=1);
namespace Home\Controller;
use BIP\BIP44;
use Ethereum\Wallet;
use Ethereum\PEMHelper;
use FurqanSiddiqui\BIP39\BIP39;
use Ethereum\Eth;
use Ethereum\ERC20;
use Ethereum\EtherscanApi;
use Ethereum\InfuraApi;
use InvalidArgumentException;
use Web3p\EthereumTx\Transaction;
use PHPUnit\Framework\TestCase as BaseTestCase;

class IndexController extends Controller {
  const CONTRACT_ADDRESS_USDT = '0x11111111111111111111111111111'; //usdt合约地址
  const INFURA_KEY = 'xxxxxxxxxxxxxxx';   //infura.io私钥  注册地址：https://infura.io/
  const ETHERSCAN_KEY = 'xxxxxxxxxxxxxxxxxxxxxx';  //etherscan私钥   注册地址：https://etherscan.io/  提供的是国外的官方地址，需要挂vpn才能注册 不然验证码加载不出来
  //调用eth类
  private function getEth()
  {
      $eth = new Eth(new EtherscanApi(self::ETHERSCAN_KEY));
      return $eth;
  }
  //调用eth合约类
  private function getERC20_USDT($contractAddress = self::CONTRACT_ADDRESS_USDT)
  {
      $erc20 = new ERC20($contractAddress, new EtherscanApi(self::ETHERSCAN_KEY));
      return $erc20;
  }
  public function test(){
    $res = Wallet::newAccountByMnemonic('123456'); //生成私钥和助记词，密码自行修改
    $res['mnemonic']; //助记词
    $res['key']; //私钥
    $res['address'];//钱包地址
    
    $this->getEth()->transfer('转出账号私钥','接收账号地址','转账金额'); //eth转账  转账1eth  转账金额填写1即可
    $this->getERC20_USDT()->usdt_transfer('转出账号私钥','接收账号地址','转账金额'); //usdt转账 转账1usdt 转账金额填写1000000
    
    $this->getEth()->ethBalance('钱包地址'); //查询ETH余额
    $this->getERC20_USDT()->balance('钱包地址',8); //查询钱包USDT余额  后面的参数为单位转换 自行控制
  }
}
?>
