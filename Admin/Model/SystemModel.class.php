<?php
/**
 * 系统模型
 * wbq@xlh-tech.com
 * 2016-04-18
 */
namespace Admin\Model;

class SystemModel extends CommonModel
{
	public function __construct()
	{
		parent::__construct();
	}

	//系统配置参数值格式化
	public function configvalueFormat($configtype=null, $configvalue=null)
	{
		switch ($configtype) {
			case 'int':
				$configvalue = (int)$configvalue;
				break;
			case 'float':
				$configvalue = (float)$configvalue;
				break;
			case 'string':
				$configvalue = (string)$configvalue;
				break;
			case 'json':
				$configvalue = json_decode($configvalue, true);
				break;
			default:
				break;
		}

		return $configvalue;
	}

	//获取系统配置
	public function getSystemConfig($configtitle=null, $configkey=null)
	{
		$where = array();
		if ($configtitle) $where['configtitle'] = array('like', '%'.$configtitle.'%');
		if ($configkey) $where['configkey'] = $configkey;

		$result = M('system_config')->where($where)->select();
		$data = array();
		if (is_array($result)) {
			foreach ($result as $d) {
				//解析configvalue
				$d['configvalue'] = $this->configvalueFormat($d['configtype'], $d['configvalue']);

				$data[$d['configkey']] = $d;
			}
		}

		return $data;
	}

	//获取系统配置-通过configkey
	public function getSystemConfigByKey($configkey=null)
	{
		if (!$configkey) return false;

		$configinfo = $this->getSystemConfig(null, $configkey);
		
		return isset($configinfo[$configkey]) ? $configinfo : array();
	}

	//保存系统配置
	public function saveSystemConfig($data=array())
	{
		if (!is_array($data)||empty($data)) return false;

		$DBObj = M('system_config');
		$DBObj->startTrans();

		$results = array();
		foreach ($data as $configkey=>$configdata) {
			$results[] = M('system_config')->where(array('configkey'=>$configkey))->save($configdata);
		}

		foreach ($results as $result) {
			if (!$result) {
				$DBObj->rollback();
				return false;
			}
		}

		$DBObj->commit();
		return true;
	}

	//获取图层信息
	public function getLayer($layerid=null, $title=null, $start=0, $length=9999)
	{
		$where = array();
		if ($layerid) $where['layerid'] = $layerid;
		if ($title) $where['title'] = array('like', '%'.$title.'%');

		$total = M('layer')->where($where)->count();
		$data = M('layer')->where($where)->select();

		return array('total'=>$total, 'data'=>is_array($data)?$data:array());
	}

	//获取图层信息 通过ID
	public function getLayerByID($layerid=null)
	{
		if (!$layerid) return false;

		$layerinfo = $this->getLayer($layerid);

		return $layerinfo['total'] ? current($layerinfo['data']) : array();
	}

	//保存图层信息
	public function savelayer($layerid=null, $data=array())
	{
		if (!is_array($data) || empty($data)) return false;

		if ($layerid) {
			$result = M('layer')->where(array('layerid'=>$layerid))->save($data);
		} else {
			$result = M('layer')->add($data);
		}

		return $result;
	}
}