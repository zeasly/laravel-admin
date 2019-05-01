<?php

namespace App\Http\Controllers;

use App\Events\OrderPayed;
use App\Events\OrderStatusChange;
use App\Jobs\DoRefund;
use App\Jobs\OrderCheckPay;
use App\Jobs\SendNotify;
use App\Jobs\SendWechatTemplate;
use App\Models\City;
use App\Models\Company;
use App\Models\CompanyAccount;
use App\Models\Complain;
use App\Models\Config;
use App\Models\ConfigTime;
use App\Models\Coupon;
use App\Models\CouponTemplate;
use App\Models\Department;
use App\Models\Feedback;
use App\Models\FinanceAccount;
use App\Models\Manager;
use App\Models\Message;
use App\Models\MessageRead;
use App\Models\Order;
use App\Models\OrderLog;
use App\Models\PayOrder;
use App\Models\PayOrderRefund;
use App\Models\Provider;
use App\Models\Refund;
use App\Models\Role;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\ServiceStore;
use App\Models\Store;
use App\Models\StoreScope;
use App\Models\StoreTime;
use App\Models\SystemLog;
use App\Models\T;
use App\Models\User;
use App\Models\Withdraw;
use App\Service\CircleToPolygon;
use App\Service\Sms;
use App\Services\Getui;
use DeepCopy\f001\A;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Response\QrCodeResponse;
use Grimzy\LaravelMysqlSpatial\Types\Point;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TestController extends ApiController
{
    public function index()
    {
        // $account = FinanceAccount::find(1);
        // $account->load('user');
        // dd($account);
        $store = Store::find(1);
        // $store->load('financeAccount');
        dd($store->financeAccount);

        return $this->order();
    }

    public function index2()
    {
        $data1 = [
            "name"         => "小明de家政服务",
            "contact_name" => "小明",
            "phone"        => "18200380990",
            "description"  => "店铺简介详情",
            "top_imgs"     => "107,108",
        ];
        //dd(json_encode($data1,JSON_UNESCAPED_UNICODE));
        $data2 = [
            "bank"           => "中国银行",
            "bank_name"      => "中国银行高新支行",
            "bank_card"      => "51113313131545464",
            "account_name"   => "开户名",
            "reserve_mobile" => "预留电话",
        ];
        //dd(json_encode($data2,JSON_UNESCAPED_UNICODE));
        $data3_1 = [
            'type'        => '1',
            'name'        => '小明',
            'identity_id' => '511181199202085312',
            'imgs1'       => '107,108',
        ];
        //dd(json_encode($data3_1,JSON_UNESCAPED_UNICODE));

        $data3_2 = [
            'type'            => '2',
            'name'            => '小明经营者/法人姓名',
            'identity_id'     => '511181199202085312经营者/法人证件号码',
            'imgs1'           => '107,108',
            'license_name'    => '执照名称',
            'credit_code'     => '统一社会信用代码',
            'license_address' => '营业执照所在地',
            'license_type'    => '1',
            'license_date'    => '2020-02-09',
            'imgs2'           => '107,108',
        ];
        dd(json_encode($data3_2, JSON_UNESCAPED_UNICODE));
        $data3_3 = [
            'type'            => '3',
            'name'            => '小明经营者/法人姓名',
            'identity_id'     => '511181199202085312经营者/法人证件号码',
            'imgs1'           => '107,108',
            'license_name'    => '执照名称',
            'credit_code'     => '统一社会信用代码',
            'license_address' => '营业执照所在地',
            'license_type'    => '1',
            'license_date'    => '2020-02-09',
            'imgs2'           => '107,108',
        ];
    }

    public function serviceApply()
    {
        $service = Service::initById(174);
        $service->name = '测试审核';
        $apply = $service->checkApply();
        if ($apply) {
            $apply->save();
        }
        $service->save();
        dd($apply);
    }

    public function service()
    {
        $service = new Service();
        $service->group = Service::GROUP_COMMON;
        $service->fill([
            'name'          => '测试代码添加',
            'title'         => '测试代码添加',
            'min_number'    => 1,
            'imgs'          => [],
            'price_type'    => 1,
            'virtual_sales' => 100,
        ]);
        $service->setCategoryAttribute(21);
        $service->setStoreAttribute(21);
        $service->setUnitAttribute(2);
        $service->setTypeAttribute(2);
        $service->save();

        //处理扩展部分
        $extend = [
            'detail'  => '详情',
            'content' => '内容',
            'imgs'    => [],
            'parts'   => [],
        ];
        $service->extend->fill($extend);
        $service->extend->save();

        $sku = $service->createSku();
        $sku->name = '订金';
        $sku->price = 10;
        $sku->save();


        $service->updateMinPrice();
        $service->save();

        $service->commonServiceStore->save();
    }

    public function storeScope()
    {
        $build = ServiceStore::queryPoint(116.401672, 39.922364);
        dd($build->toSql(), $build->getBindings());
        $area = [
            ['lng' => 116.387112, 'lat' => 39.920977],
            ['lng' => 116.385243, 'lat' => 39.913063],
            ['lng' => 116.394226, 'lat' => 39.917988],
            ['lng' => 116.401772, 'lat' => 39.921364],
            ['lng' => 116.41248, 'lat' => 39.927893],
        ];
        $area2 = [
            ['lng' => 118.387112, 'lat' => 39.920977],
            ['lng' => 118.385243, 'lat' => 39.913063],
            ['lng' => 118.394226, 'lat' => 39.917988],
            ['lng' => 118.401772, 'lat' => 39.921364],
            ['lng' => 118.41248, 'lat' => 39.927893],
        ];
        $scope = new StoreScope();
        $scope->addArea($area);
        $scope->store()->associate(1);
        $scope->save();
    }

    public function coupon()
    {
        CouponTemplate::initById(5)->generate(3)->save();
    }

    public function orderCoupon()
    {
        try {
            $order = new Order();
            $order->setUserAttribute(3);
            $order->setServiceSku(91);//普通
            // $order->setServiceArea(2);//特约
            $order->setStoreAttribute(14);//店铺
            // $order->setAreaAttribute(510100);
            $order->setAddress(74);
            $order->initStatus();
            $order->useCoupon(36);
            dd($order);
            $order->save();
            $order->service->save();
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function push()
    {
        /** @var Getui $push */
        $push = app('getui.company');
        // $push = app('getui.provider');
        // dd($push->getAliasDevices(1));
        // dd($push->getDevices('47603baef5908ee9d2356ebcaa1d713b'));
        dd($push->toAlias(1, '测试推送', '测试推送'));
        dd($push->updateAlias('41884ae4941669c9a185991e817c8d5a', '1'));
        // dd($push->toAll('测试推送', '测试推送'));
    }

    public function cache()
    {
        dd(Cache::get($this->request->get('key')));
    }

    public function withDraw()
    {
        $store = Store::initById(11);
        $withdraw = $store->withdraw()->make();
        $withdraw->amount = $store->canWithdrawAmount();
        $withdraw->save();
    }

    public function finance()
    {
        // $finance = FinanceAccount::platform();
        $store = Store::initById(11);
        $finance = $store->financeAccount;
        $finance->change(100, 'system', '测试虚拟充值');
        dd($finance);
    }

    public function refund()
    {
        // $refund = PayOrderRefund::initById(16);
        // dd($refund->handleResult($refund->getResult()));
        // $payOrder = PayOrder::initById(509);
        // $refund = $payOrder->createRefund(0.01, '测试');
        // dd($refund->doRefund());

        //通过订单创建退款
        // $order = Order::initById(329);
        // $refund = $order->createRefund();
        // dd($refund);
        // // $refund->amount = $order->getRefundAmountAttribute();
        // $refund->amount = '0.01';
        // $refund->save();
        // dd($refund);

        $refund = Refund::initById(22);
        $refund->doRefund('test');
        // dispatch(new DoRefund($refund));
        // $payOrder = PayOrder::initById(60);
        // $refund = $payOrder->createRefund($returnAmount, $remark);
        // $refund->doRefund();
    }

    public function orderPay()
    {
        // dd((new QrCode('asdfasdf'))->writeFile());
        // $payOrder = PayOrder::initById(4);
        // dd($payOrder->getInfo());

        $order = Order::initById(201);
        $payOrder = $order->createPayOrder('测试', 0.01);
        // $re = $payOrder->jssdk('opswE5jpeM81QoX6UFibokZ88dsc', 'mini');
        $re = $payOrder->webPay();
        dd($re);
        // $payOrder->save();

    }

    /**
     * 调用短信发送
     * $msg->getData()->code 获取状态码 0=失败 1=成功
     * $msg->getData()->message 文字提示信息
     * @author 穆风杰<hcy.php@qq.com>
     */
    public function sendSms()
    {
        $sms = new Sms();
        $msg = $sms->send('18200380990', '您的验证码是：123456');
        dd($msg);
    }

    public function order()
    {
        try {
            $order = new Order();
            // $order->setUserAttribute(1);
            // $order->setServiceSku(2);//普通
            // $order->setServiceArea(2);//特约
            // $order->setStoreAttribute(11);//店铺
            $order->setCityAttribute(510100);
            dd($order);
            $order->initStatus();
            $order->save();
            $order->service->save();
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function role()
    {
        $manager = Manager::find(1);
        $manager->role = 3;
    }

    public function putDataJson()
    {
        set_time_limit(0);
        $tree = Area::getTree();
        //dd($tree);
        $info = file_put_contents('js/cx_select/cityData2.json', $tree->toJson());
        dd($info);
    }


    public function department()
    {
        $list = Department::withCount('manager')->get();
        $list = $list->toTree();
        foreach ($list as $v) {
            var_dump($v->all_manager_count);
        }
        // $list->each(function($v){
        //     $v->getAllManagerCount();
        // });
        dd($list);
    }

    public function addDepartment()
    {
        $parent = Department::initById(3);
        $dep = Department::create(['name' => '部门111'], $parent);
        $dep->save();
    }

    public function getDepanrment()
    {
        $result = Department::defaultOrder()->descendantsAndSelf(1);
        dd($result->toArray());
    }

    public function getDepTree()
    {
        $result = Department::defaultOrder()->get()->toTree()->toArray();
        dd($result);
    }

}
