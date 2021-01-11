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
  /*
  上面举例tp3.2用法 其他版本 请自行修改
  1 文件全部下载后放入项目根目录
  2 使用方法 此处只写了eth和usdt代币的简单方法 其他币种请自行修改
  3 两个免费接口可自行切换 nanqi/test里面有调用方法 默认使用etherscan
  4 归集说明 归集其实就是将用户从其他平台转入到你生成的钱包中的钱 转入到你的一个钱包地址中
  5 举例说明： 钱包A是imtoken生成  钱包B是你的APP生成  钱包C是你设定的归集地址
    B用户在平台没有钱 需要通过其他平台充值
    用户从A转入10个USDT转入B B收到USDT后 再将USDT转入C 就是归集
  6 如果用此扩展做归集功能 接口没有批量转账功能（我没找到，如果有找到的可以无视下面） 所以只能一条一条归集
  7 此处以usdt举例 我的归集流程是
    定时查询用户钱包地址下所有的USDT订单存入一张表
    然后判断该笔订单是否为转入订单
    如果是转入订单，代表用户想充值到你自己的APP钱包中
    然后查询你自己的APP钱包中是否有USDT余额，并且金额大于等于订单表中存储的充值金额 此处必须判断余额是否大于等于充值金额 因为私钥可在其他平台上使用 避免未归集的金额被用户通过其他平台转走
    如果金额符合 查询app钱包中是否有eth足够支付gas 如果不够 先给app钱包转入一部分eth 可定时查询此次手续费转账状态 转账成功后 继续归集
    将app钱包中的USDT余额转入到归集地址中 继续定时查询归集状态 转入成功后 归集完成 APP给用户的余额字段加上对应金额即可
  */
}
?>
