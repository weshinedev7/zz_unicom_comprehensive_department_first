<?php
namespace Admin\Controller;
use Think\Controller;
use Think\Model;

class OrderController extends Controller {
    public $_GPC = array();

    public function _initialize(){
        $this->_GPC = array_merge($_POST, $_GET);
    }
    
	public function index(){
        $_GPC=$this->_GPC;
        $Model=new Model();
		if(session('username') != null)
		{
		    $searchtype=trim($_GPC['searchtype']);
		    $keyword=trim($_GPC['keyword']);
		    $condition=" u.openid=o.openid and orderdelete=0 ";
            $ordertype=!empty(trim($_GPC['ordertype'])) ? trim($_GPC['ordertype']) : '';

		    if (!empty($searchtype) && !empty($keyword)){
                switch ($searchtype){
                    case 'nickname' :
                        $condition.=" and u.nickname like '%".$keyword."%' ";
                        break;
                    case 'mobile' :
                        $condition.=" and o.mobile like '%".$keyword."%' ";
                        break;
                    case 'ordersn' :
                        $condition.=" and o.ordersn like '%".$keyword."%' ";
                        break;
                    case 'expresssn' :
                        $condition.=" and o.expressno like '%".$keyword."%' ";
                        break;
                    default :
                        $condition.=" ";
                        break;
                }
            }
            $order="";
            switch ($ordertype){
                case 'createtime' :
                    $order.=" o.createtime desc ";
                    break;
                case 'status' :
                    $order.=" o.status desc, o.createtime desc  ";
                    break;
                default :
                    $order.=" o.id desc ";
                    break;
            }

            $count=$Model->table(array('wx_order'=>'o','wx_user'=>'u'))->where($condition)->field(" o.*,u.nickname,u.avatar ")->select();


            $Page = new \Think\Page(count($count),10);
            $show = $Page->show();
            $firstRow=$Page->firstRow;
            $listRows=$Page->listRows;
            $list=$Model->table(array('wx_order'=>'o','wx_user'=>'u'))->where($condition)->field(" o.*,u.nickname,u.avatar ")->order($order)->limit($firstRow.' , '.$listRows)->select();
            foreach($list as &$row){
                $row['createtime']=empty($row['createtime'])? '' : date('Y-m-d',$row['createtime']);
                $row['paytime']=empty($row['paytime'])? '' : date('Y-m-d',$row['paytime']);
                $row['sendtime']=empty($row['sendtime'])? '' : date('Y-m-d',$row['sendtime']);
                $row['finishtime']=empty($row['finishtime'])? '' : date('Y-m-d',$row['finishtime']);
                //2019-02-20 微信昵称   by xd
                $row["nickname"]=!empty($row["nickname"])?rawurldecode($row["nickname"]):"";
                switch ($row['type']){
                    case '1' : //每日科学
                        $row['goodsinfo']=M('science')->where(array('id'=>$row['goodsid']))->field('name')->find();
                        break;
                    case '2' ://专辑故事
                        $row['goodsinfo']=M('album')->where(array('id'=>$row['goodsid']))->field('name')->find();
                        break;
                    case '3' ://徽章
                        $row['goodsinfo']=$Model->table(array('wx_badge'=>'b','wx_user_badge'=>'u'))->where(' b.id=u.badgeid and u.id='.$row['goodsid'].' ')->field(" b.name ")->find();
                        break;
                }

            }
            //2019-02-20 by xd
            unset($row);
            //物流公司
            $express=M("company")->field("code,name")->select();
            $this->assign('list',$list);
            $this->assign("express",$express);
            $this->assign('page',$show);
            $this->assign('keyword',$keyword);
            $this->assign('ordertype',$ordertype);
            $this->assign('searchtype',$searchtype);
			$this->display();
		}
    }


    public function del()
    {
        header("Content-Type:text/html; charset=utf-8");
        $id=$_POST["id"];
        $Order = M("order"); // 实例化User对象
        $result=$Order->where(array('id'=>$id))->save(array('orderdelete'=>1));
        $arr['flag']=0;
        if($result)
        {
            $arr['flag']=1;
            $this->ajaxReturn($arr,json);
        }
        else
        {
            $this->ajaxReturn($arr,json);
        }
    }

    public function edit(){
        $_GPC=$this->_GPC;
         $shippercode=trim($_GPC['shippercode']);
         $expressno=trim($_GPC["expressno"]);
         $id=trim($_GPC['id']);
         if (!empty($shippercode) && !empty($expressno) && !empty($id)){
            $res=M('order')->where(array('id'=>$id))->save(array('status'=>3,'expressno'=>$expressno,"shippercode"=>$shippercode));
            if ($res){
                $flag=1;
            }else{
                $flag=-1;
            }
         }else{
            $flag=-1;
         }
            $this->ajaxReturn(array('status'=>$flag),json);

    }

    public function pay(){
        header("Content-Type:text/html; charset=utf-8");
        $id = $_POST["id"];
        $Model = new Model(); // 实例化一个model对象 没有对应任何数据表
        $result = $Model->execute("update wx_order set status=3 where id=$id");
        $arr['flag'] = 0;
        if ($result) {
            $arr['flag'] = 1;
            //进行数据封装
            $this->ajaxReturn($arr, json);
        } else {
            $this->ajaxReturn($arr, json);
        }
    }


    public function updatePrice()
    {
        header("Content-Type:text/html; charset=utf-8");
        $Order=M("order");
        $id= $_POST["id"];
        $data["ordernum"] = date('YmdHis',time()).$this->GetRandStr(5);
        $data["price"] = $_POST["price"];
        $arr['flag']=0;
        if($Order->create())
        {
            $result=$Order->where("id=$id")->save($data);
            if($result)
            {
                $arr['flag']=1;
                $this->ajaxReturn($arr,json);
            }
            else
            {
                $this->ajaxReturn($arr,json);
            }
        }
    }
    public function excelimport(){
        if (!empty($_FILES)){
            $companyModel=M('company');
            $companyModel->where('id>0')->delete();

            require './ThinkPHP/Library/Vendor/PHPExcel/Classes/PHPExcel.php';
            require './ThinkPHP/Library/Vendor/PHPExcel/Classes/PHPExcel/Shared/Date.php';
            // 上传文件

            $filename = 'Public/upload/excel/'.time().$_FILES["file"]["name"];
            if(!$filename){
                mkdir($filename);
            }
            move_uploaded_file($_FILES["file"]["tmp_name"], $filename);

            $exts   = end(explode('.', $_FILES["file"]["tmp_name"]));  // 生成文件路径名

            if ($exts == 'xls') {                                        // 如果excel文件后缀名为.xls，导入这个类
                vendor("PHPExcel.PHPExcel.Reader.Excel5");
                $PHPReader = new \PHPExcel_Reader_Excel5();
            } else{
                vendor("PHPExcel.PHPExcel.Reader.Excel2007");
                $PHPReader = new \PHPExcel_Reader_Excel2007();
            }

            $shared = new \PHPExcel_Shared_Date();
            $PHPExcel = $PHPReader->load($filename);

            $currentSheet = $PHPExcel->getSheet(0);               // 获取表中的第一个工作表，如果要获取第二个，把0改为1，依次类推
            $allColumn = $currentSheet->getHighestColumn();              // 获取总列数
            $allRow = $currentSheet->getHighestRow();                    // 获取总行数
            $data=array();
            for($j=1;$j<=$allRow;$j++){
                //从A列读取数据
                for($k='A';$k<=$allColumn;$k++){
                    // 读取单元格
                    $cell=$PHPExcel->getActiveSheet()->getCell("$k$j")->getValue();
                    if(is_object($cell)) {
                        $cell= $cell->__toString();
                    }

                    $data[$j][]=$cell;
                    unset($cell);
                }
            }


            for ($i=2; $i <= $allRow; $i++){
                $tempinsertdata=array(
                    'code'=>$data[$i][1],
                    'name'=>$data[$i][0],
                );
                #todo 如果要以防万一.就检测一下是否有
                //$isexist=$goodsModel->where(array('code'=>$tempinsertdata['code'],'name'=>$tempinsertdata['name'],'goodcode'=>$tempinsertdata['goodcode']))->find();
                /*if (empty($isexist)){
                    $res=$goodsModel->add($tempinsertdata);
                }*/
                $res=$companyModel->add($tempinsertdata);


            }



            if($res){
                //                $this->redirect("Esuccess/index");
                $this->redirect("Order/index");
            }else{
                $this->error("导入失败，原因可能是excel表中格式错误","5");// 提示错误
            }

        }
    }



	
}