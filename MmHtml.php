<?php
/**
 * html
 *
 * @author mmfei<wlfkongl@163.com>
 */
class MmHtml
{
    /**
     * 绘制表格
     * @param array $arrTh	标题  eg :
     * 								array(
     * 									array('title'=>'标题名称','tpl'=> '模板文本','tagAttrs'=>array(),),//{字段名}:表示对应字段的数值
     * 									array('title'=>'标题名称','tpl'=> '模板文本','tagAttrs'=>array(),),//{字段名}:表示对应字段的数值
     * 									array('title'=>'标题名称','tpl'=> '模板文本','tagAttrs'=>array(),),//{字段名}:表示对应字段的数值
     * 									...
     * 								),
     * @param array $data	数据  eg : array(array('Key列关键字' => '数据',...),...)
     * @param boolean $isForm 是否需要表单
     * @param string $formUrl 表单提交目标
     * @param string $submitText 表单提交文字
     */
    public function DataShow(array $arrTh , array $data , $isForm = false , $formUrl = '' , $submitText = '确定')
    {
        $th = '';
        $count = 0;
        if($isForm)
        {
            $col = count($arrTh);
            $table = "<form name='form1111' action='{$formUrl}' method='post'>
						<table class='table'>
							<thead>{__th__}</thead>
							<tbody>
								{__body__}
								<tr style='background-color: rgb(255, 255, 255); '>
									<td colspan='{$col}' style='text-align:center;'>
										<input type='submit' value='{$submitText}'>
									</td>
								</tr>
							</tbody>
						</table>
					</form>";
        }
        else
        {
            $table = "<table class='table'>
						<thead>{__th__}</thead>
						<tbody>
							{__body__}
						</tbody>
					</table>";
        }
        $body = '';
        $arrts = array();
        foreach($arrTh as $key => $arr)
        {
            $value = is_array($arr) && isset($arr['title']) ? $arr['title'] : $arr;

            if(is_array($arr))
            {
                if(isset($arr['tagAttrs']))
                {
                    foreach($arr['tagAttrs'] as $kk => $vv)
                    {
                        isset($arrts[$value]) ? $arrts[$value] .= " {$kk} = '{$vv}'" : $arrts[$value] = " {$kk} = '{$vv}'";
                    }
                }
            }
            $attrsText = isset($arrts[$value]) ? $arrts[$value] : '';
            $th.="<th{$attrsText}>{$value}</th>";
        }
        $arrReplace = $a = array();
        foreach($data as $arr)
        {
            $a = $arr;
            break;
        }
        foreach($a as $k => $v)
        {
            $arrReplace[$k] = "{{$k}}";
        }
        unset($a);
        foreach(array_values($data) as $key => $arr)
        {
            if($key && $key % 10 == 0)
                $body.='{__th__}';
            foreach($arr as $k1=>$v1)
                if(is_array($v1) || is_object($v1))
                    $arr[$k1] = json_encode($v1);
            $body.="<tr>";
            $arrValues = array();
            foreach($arrReplace as $k => $v)
            {
                $arrValues[$k] = isset($arr[$k]) ? $arr[$k] : '';
            }
            foreach($arrTh as $k => $v)
            {
                $attrsText = is_array($v) && isset($arrts[$v['title']]) ? $arrts[$v['title']] : '';
                $tpl = is_array($v) && isset($v['tpl']) ? $v['tpl'] : $v;

                $body.="<td{$attrsText}>".str_replace($arrReplace, $arrValues, $tpl)."</td>";
            }
            $body.="</tr>";
        }
        $table = str_replace(array('{__body__}','{__th__}',), array($body,$th,), $table);
        return self::AppendFooter($table)->InitDefaultCss()->InitDefaultJs();
    }
    /**
     * 绘制表格
     * @param array $arrTh	标题  eg :
     * 								array('Key列关键字' => '标题名称',...)
     * 								或者
     * 								array(
     * 									'Key列关键字' => array('title'=>'标题名称','tpl'=>'模板文本',)
     * 									'Key列关键字' => array('title'=>'标题名称','tpl'=>'模板文本',)
     * 									'Key列关键字' => array('title'=>'标题名称','tpl'=>'模板文本',)
     * 									,...
     * 								)
     * @param array $data	数据  eg : array(array('Key列关键字' => '数据',...),...)
     * @param boolean $isForm 是否需要表单
     * @param string $formUrl 表单提交目标
     * @param string $submitText 表单提交文字
     */
    public function DataToTable(array $arrTh , array $data , $isForm = false , $formUrl = '' , $submitText = '确定')
    {
        $th = '';
        $count = 0;
        if($isForm)
        {
            $col = count($arrTh);
            $table = "<form name='form1111' action='{$formUrl}' method='post'><table class='table'>
					<thead>{__th__}</thead>
					<tbody>
						{__body__}
						<tr style='background-color: rgb(255, 255, 255); '>
							<td colspan='{$col}' style='text-align:center;'>
								<input type='submit' value='{$submitText}'>
							</td>
						</tr>
					</tbody>
				</table></form>";
        }
        else
        {
            $table = "<table class='table'>
						<thead>{__th__}</thead>
						<tbody>
							{__body__}
						</tbody>
					</table>";
        }
        $body = '';
        foreach($arrTh as $key => $value)
        {
            if(is_array($value))
            {
                $attrs = isset($value['attrs']) && is_array($value['attrs']) ? $value['attrs'] : array();
                $titleName = isset($value['title']) ? $value['title'] : array_pop($value);
                $attrString = '';
                foreach ($attrs as $k1 => $v1)
                    if(is_numeric($k1))
                        $attrString.= " {$v1}";
                    else
                        $attrString.=" {$k1} = '{$v1}'";
                $th.="<th{$attrString}>{$titleName}</th>";
            }
            else
            {
                $th.="<th>{$value}</th>";
            }
        }
        $arrReplace = $a = array();
        foreach($data as $arr)
        {
            $a = $arr;
            break;
        }
        foreach($a as $k => $v)
        {
            $arrReplace[] = "{{$k}}";
        }
        unset($a);
        foreach($data as $key => $arr)
        {
            foreach($arr as $k=>$v)
                if(is_array($v) || is_object($v))
                    $arr[$k] = json_encode($v);
            $body.="<tr>";
            foreach($arrTh as $k => $v)
            {
                if(is_array($v))
                {
                    $tpl = isset($v['tpl']) ? $v['tpl'] : array_pop(array_pop($v));
                    $value = $v;
                    $attrs = isset($value['attrs']) && is_array($value['attrs']) ? $value['attrs'] : array();
                    $attrString = '';
                    foreach ($attrs as $k1 => $v1)
                        if(is_numeric($k1))
                            $attrString.= " {$v1}";
                        else
                            $attrString.=" {$k1} = '{$v1}'";
                    $body.="<td{$attrString}>".str_replace($arrReplace, $arr, $tpl)."</td>";
                }
                else
                {
                    $body.="<td>{$arr[$k]}</td>";
                }
            }
            $body.="</tr>";
        }
        $table = str_replace(array('{__th__}','{__body__}'), array($th , $body), $table);
        return self::AppendFooter($table)->InitDefaultCss()->InitDefaultJs();
    }
    /**
     * 初始化html对象，清空html内容
     *
     * @param string $content		body的内容
     * @param string $title			html的标题
     * @param string $javascript	javascript脚本内容
     * @param string $css			css内容
     * @return void
     */
    public function __construct($content = '', $title = '', $javascript = '', $css = '')
    {
        $GLOBALS['mmHtml']['Css'] 			= $css;
        $GLOBALS['mmHtml']['Javascript'] 	= $javascript;
        $GLOBALS['mmHtml']['JavascriptFile']= array();
        $GLOBALS['mmHtml']['Body'] 			= $content;
        $GLOBALS['mmHtml']['Title'] 		= $title;
        $GLOBALS['mmHtml']['Form']			= array();
        $GLOBALS['mmHtml']['Meta']			= '';
        $GLOBALS['mmHtml']['Footer']		= '';
    }
    /**
     * 初始化表单
     *
     * @param string $formName		表单名称
     * @param string $title			网页标题
     * @param string $formActionUrl	网页提交url
     * @param string $buttonText	表单按钮文本内容
     * @param boolean $isAjax		是否ajax提交(提交的url必须返回[0:代表失败,1:代表成功])
     * @param array $attr			form的属性
     * @return void
     */
    public static function InitForm($formName, $title , $formActionUrl , $buttonText = '确定' , $isAjax = false ,array $attr = array())
    {
        //添加表单
        $html = Html::GetHtml();
        $method = 'post';
        $action = $formActionUrl;
        $caption = $title;
        $arrForm = array();
        if($isAjax)
            $arrForm['class'] = 'ajaxForm';
        foreach($attr as $k => $v)
        {
            $arrForm[$k] = $v;
        }
        $html->Form($method, $formName , $action , $caption , null , null , 2 , $buttonText , $arrForm)
            ->Title($title)
            ->InitDefaultCss()->InitDefaultJs()->InitAjaxSubmit();
    }
    /**
     * 获取当前html对象，不清空html内容
     *
     * $formActionUrl = '';
    $title = '修改会员';
    $formName = 'edit';
    $html->InitForm($formName, $title, $formActionUrl,'修改',false,array('enctype'=>'multipart/form-data',))
     */
    public static function GetHtml()
    {
        if(isset($GLOBALS['mmHtml']))
        {
            $mmHtml = $GLOBALS['mmHtml'];
            $html = new self();
            $GLOBALS['mmHtml'] = $mmHtml;
            return $html;
        }
        return new self();
    }
    /**
     * 获取GET的数据
     *
     * @param string $s			需要获取的get变量 $_GET[$s];
     * @param string $defualt	如果get变量不存在则返回指定数值
     * @return string
     */
    public static function Get($s , $defualt = null)
    {
        return isset($_GET[$s]) ? $_GET[$s] : $defualt;
    }
    /**
     * 获取POST的数据
     *
     * @param string $s			需要获取的post变量 $_POST[$s];
     * @param string $defualt	如果post变量不存在则返回指定数值
     * @return string
     */
    public static function Post($s , $defualt = null)
    {
        return isset($_POST[$s]) ? $_POST[$s] : $defualt;
    }
    /**
     * 获取POST | GET的数据 [POST优先]
     *
     * @param string $s			需要获取的post | get 变量 $POST[$s] $_GET[$s];
     * @param string $defualt	如果post | get变量不存在则返回指定数值
     * @return string
     */
    public static function PG($s , $defualt = null)
    {
        return isset($_POST[$s]) ? $_POST[$s] : (isset($_GET[$s]) ? $_GET[$s] : $defualt);
    }
    /**
     * 追加css内容
     *
     * @param string $s	css 内容
     * @return Html
     */
    public function AppendCss($s)
    {
        isset($GLOBALS['mmHtml']['Css']) ? '' : $GLOBALS['mmHtml']['Css'] = '' ;
        $GLOBALS['mmHtml']['Css'].=$s;
        return $this;
    }
    /**
     * 覆盖css内容，会覆盖以前的css内容
     *
     * @param string $s	css 内容
     * @return Html
     */
    public function Css($s)
    {
        isset($GLOBALS['mmHtml']['Css']) ? '' : $GLOBALS['mmHtml']['Css'] = '' ;
        $GLOBALS['mmHtml']['Css']=$s;
        return $this;
    }
    /**
     * 追加js内容
     *
     * @param string $s	js 内容
     * @return Html
     */
    public function AppendJavascript($s)
    {
        isset($GLOBALS['mmHtml']['Javascript']) ? '' : $GLOBALS['mmHtml']['Javascript'] = '' ;
        $GLOBALS['mmHtml']['Javascript'].=$s;
        return $this;
    }
    /**
     * 增加一个js文件
     *
     * @param string $src	js文件路径
     * @return Html
     */
    public function AppendJavascriptFile($src)
    {
        isset($GLOBALS['mmHtml']['JavascriptFile']) ? '' : $GLOBALS['mmHtml']['JavascriptFile'] = array() ;
        $GLOBALS['mmHtml']['JavascriptFile'][] = $src;
    }
    /**
     * 覆盖js内容
     *
     * @param string $s	js 内容
     * @return Html
     */
    public function Javascript($s)
    {
        $GLOBALS['mmHtml']['Javascript']=$s;
        return $this;
    }

    /**
     * 追加js正文内容，body标签中间的内容
     *
     * @param string $s	body内容
     * @return Html
     */
    public function AppendBody($s)
    {
        isset($GLOBALS['mmHtml']['Body']) ? '' : $GLOBALS['mmHtml']['Body'] = '' ;
        $GLOBALS['mmHtml']['Body'].=$s;
        return $this;
    }
    /**
     * 追加body底部内容，在绘制完AppendBody后继续绘制的内容<body>设置这里的内容</body>
     *
     * @param string $s	footer内容
     * @return Html
     */
    public function AppendFooter($s)
    {
        isset($GLOBALS['mmHtml']['Footer']) ? '' : $GLOBALS['mmHtml']['Footer'] = '' ;
        $GLOBALS['mmHtml']['Footer'].=$s;
        return $this;
    }
    /**
     * 设置body的内容[不包括Footer内容]<body>[其他内容] 设置这里的内容</body>
     *
     * @param string $s	body内容
     * @return Html
     */
    public function Body($s)
    {
        $GLOBALS['mmHtml']['Body']=$s;
        return $this;
    }
    /**
     * 设置网站标题<title>[其他已经设置的标题] 设置这里的内容</title>
     *
     * @param string $s	title内容
     * @return Html
     */
    public function AppendTitle($s)
    {
        isset($GLOBALS['mmHtml']['Title']) ? '' : $GLOBALS['mmHtml']['Title'] = '' ;
        $GLOBALS['mmHtml']['Title'].=$s;
        return $this;
    }/**
 * 设置网站标题<title>设置这里的内容</title>
 *
 * @param string $s	title内容
 * @return Html
 */
    public function Title($s)
    {
        $GLOBALS['mmHtml']['Title']=$s;
        return $this;
    }
    /**
     * 在表单form[name=$formName]中追加一行表单数据
     * 		eg:
     * 			================================================================================
     * 			1 ) AppendInput($formName , $inputName , $inputLabel , $value , $type = 'text');
     * 				----------------------------------------------------------------------------
     * 				<form name='{$formName}'>
     * 					....
     * 					<tr>
     *	 					<td>{$inputLabel}</td>
     * 						<td><input type='{$type}' name='{$inputName}' value='{$value}' /></td>
     * 					</tr>
     * 					....
     * 				</form>
     * 				----------------------------------------------------------------------------
     *
     * 			================================================================================
     * 			2 ) AppendInput($formName , $inputName , $inputLabel , $value , $type = 'radio|checkbox' , $label , $arrSelected = array(1,) , $br = true);
     * 				----------------------------------------------------------------------------
     * 				<form name='{$formName}'>
     * 					....
     * 					<tr>
     *	 					<td>{$inputLabel}</td>
     * 						<td>
     * 							...[其他radio]
     * 							<input type='{$type}' name='{$inputName}' value='{$value}' id='{$inputName}_{$value}' /> <label for='{$inputName}_{$value}'>{$label}</label> <br />
     * 							...[其他radio]
     * 						</td>
     * 					</tr>
     * 					....
     * 				</form>
     * 				----------------------------------------------------------------------------
     * 			================================================================================
     * 			3 ) AppendInput($formName , $inputName , $inputLabel , $value , $type = 'select' , $label , $arrSelected = array(1,) , $br = true);
     * 				----------------------------------------------------------------------------
     * 				<form name='{$formName}'>
     * 					....
     * 					<tr>
     *	 					<td>{$inputLabel}</td>
     * 						<td>
     * 							<select name='{$inputName}'>
     * 								...[其他option]
     * 								<option value='' selected='???'>
     * 									{$label}
     * 								</option>
     * 								...[其他option]
     * 							</select>
     * 						</td>
     * 					</tr>
     * 					....
     * 				</form>
     * 				----------------------------------------------------------------------------
     * 			================================================================================
     * 			4 ) AppendInput($formName , $inputName , $inputLabel , $value , $type = 'textarea' , $label , $arrSelected = array(1,) , $br = true);
     * 				----------------------------------------------------------------------------
     * 				<form name='{$formName}'>
     * 					....
     * 					<tr>
     *	 					<td>{$inputLabel}</td>
     * 						<td>
     * 							<textarea name='{$inputName}'>{$value}</textare>
     * 						</td>
     * 					</tr>
     * 					....[other textarea]
     * 				</form>
     * 				----------------------------------------------------------------------------
     *
     * @param string $formName		被追加的表单的名称，eg:<form name='{$formName}'>...</form>
     * @param string $inputName		被追加到表单的input的名称 , eg:<form name='{$formName}'>...<input type=''/>...</form>
     * @param string $inputLabel
     * @param string $value
     * @param string $type
     * @param string $label
     * @param array $selected
     * @param string | boolean $br
     * @param array $attr
     * @param array $rowAttr
     * @return Html
     */
    public function AppendInput($formName , $inputName , $inputLabel = '' , $value = '' , $type = 'text' , $label = '' , $selected = array() , $br = false , $attr = array() , $rowAttr = null)
    {
        if(isset($GLOBALS['mmHtml']['Form'][$formName]))
        {
            if(!isset($GLOBALS['mmHtml']['Form'][$formName]['Input'][$inputName]))
            {
                $GLOBALS['mmHtml']['Form'][$formName]['Input'][$inputName] = array(
                    'Label'		=>	$inputLabel ,
                    'Selected'	=>	$selected,
                    'Type'		=>	$type,
                    'rowAttr'	=>	$rowAttr ? $rowAttr : array(),
                    'List'		=>	array(
                        array(
                            'Name'		=>	$inputName,
                            'Value'		=>	$value,
                            'Label'		=>	$label,
                            'Br'		=>	$br,
                            'Attr'		=>	$attr,
                            'Type'		=>	$type,
                        ),
                    ),
                );
            }
            else
            {
                if(isset($rowAttr))
                {
                    $GLOBALS['mmHtml']['Form'][$formName]['Input'][$inputName]['rowAttr']	=	$rowAttr;
                }
                $GLOBALS['mmHtml']['Form'][$formName]['Input'][$inputName]['List'][] = array(
                    'Name'		=>	$inputName,
                    'Value'		=>	$value,
                    'Label'		=>	$label,
                    'Br'		=>	$br,
                    'Attr'		=>	$attr,
                    'Type'		=>	$type,
                    'rowAttr'	=>	$rowAttr,
                );
            }
        }
        return $this;
    }
    /**
     * 追加文本到Form表单顶部
     *
     * @param string $formName	表单名称
     * @param string $htmlCode	html代码
     * @return Html
     */
    public function AppendTextToForm($formName , $htmlCode)
    {
        $GLOBALS['mmHtml']['Form'][$formName]['FormAppendText'] .= $htmlCode;
        return $this;
    }
    /**
     * 追加文本到Form表单尾部
     *
     * @param string $formName	表单名称
     * @param string $htmlCode	html代码
     * @return Html
     */
    public function AppendTextToFormBottom($formName , $htmlCode)
    {
        $GLOBALS['mmHtml']['Form'][$formName]['FormAppendTextBottom'] .= $htmlCode;
        return $this;
    }
    /**
     * 创建一个表单
     *
     * @param string $formMethod 		表单提交方式[post | get]
     * @param string $formName			表单名称
     * @param string $formAction		表单提交目标url
     * @param string $formTitle			表单标题
     * @param string $formHeadTemplate	表单头部模板字符串
     * @param string $formRowTemplate	表单单行模板字符串
     * @param integer $columnsCount		表单列数
     * @param string $sumbitText		表单按钮文本内容
     * @param array $attr				表单附加html属性 array('class'=>'a b',),#<form ...class='a b'>...</form>
     * @return Html
     */
    public function Form($formMethod , $formName , $formAction = '' , $formTitle = '' , $formHeadTemplate = null , $formRowTemplate = null , $columnsCount = null , $sumbitText = null , $attr = array())
    {
        $GLOBALS['mmHtml']['Form'][$formName] = array(
            'Method'	=>	$formMethod ,
            'Action'	=>	$formAction ? $formAction : $_SERVER['REQUEST_URI'] ,
            'Caption'	=>	$formTitle ,
            'Th'		=>	isset($formHeadTemplate) ? $formHeadTemplate : '<th colspan={Columns}>{Caption}</th>' ,
            'Tr'		=>	isset($formRowTemplate) ? $formRowTemplate : '<tr{trAttr}><td>{Label}</td><td>{Text}</td></tr>' ,
            'Columns'	=>	isset($columnsCount) ? $columnsCount : 2,
            'FormAppendText'=> '',
            'FormAppendTextBottom'=> '',
            'SumbitText'=>	isset($sumbitText) ? $sumbitText : '确定',
            'Input'		=>	array(),
            'Attr'		=>	$attr,
        );
        return $this;
    }
    /**
     * 从配置绘制表单【直接添加到body中】
     * @param array $config
     * 			array(
     * 				'formName' => 'form1',
     * 				'mothod' => 'post',
     * 			)
     * @return Html
     */
    public function FormByConfig($config)
    {
        $defaultConfig = array(
            'formName' => 'form1',
            'method' => 'post',
            'action' => '',
            'title' => '表单标题',
            'attr' => array(),//额外表单属性 , 如果有file表单 ， 则需要 enctype=>"multipart/form-data"
            'textTop' => '',//顶部文本内容
            'textBottom' => '',//底部文本内容
            'buttonText' => '确定',//按钮文字
            'inputList' => array(//表单元素 , 一个元素代表一行
// 				array(
// 					'label' => '',//表单文本
// 					'list' => array(
// 						array(
// 							'name' => '',//表单名称
// 							'type' => 'text',//表单类型 text|label|hidden|password|radio|select|checkbox|textarea|file
// 							'value' => '',//表单值
// 							'attr' => array(),//表单属性
// 							'selected' => array(),//选中值 , 多选表单有效
// 							'valueList'=>array(//此行表单的元素列表 , 多选表单有效
// 								'value' => 'text',
// 							)
// 						),
// 					),
// 				),
            ),
        );
        $newConfig = $defaultConfig;
        $isUpload = false;
        foreach($config as $k => $v)
        {
            if(is_array($v))//$k = inputLit
            {
                foreach($v as $kk => $vv)
                {
                    if(is_array($vv))
                    {
                        foreach($vv as $kkk => $vvv)//$kkk = list
                        {
                            if(is_array($vvv))
                            {
                                foreach($vvv as $kkkk => $vvvv)
                                {
                                    if(!$isUpload && isset($vvvv['type']) && $vvvv['type'] == 'file')
                                    {
                                        $isUpload = true;
                                    }
                                    $newConfig[$k][$kk][$kkk][$kkkk] = $vvvv;
                                }
                            }
                            else
                                $newConfig[$k][$kk][$kkk] = $vvv;
                        }
                    }
                    else
                    {
                        $newConfig[$k][$kk] = $vv;
                    }
                }
            }
            else
            {
                $newConfig[$k] = $v;
            }
        }
        $tbody = '';
        foreach($newConfig['inputList'] as $arrRow)
        {
            $inputString = '';
            $appendString = '';
            foreach($arrRow['list'] as $arr)
            {
                if(isset($arr['attr']) && $arr['attr'])
                    $attrs1 = self::_attrToStr($arr['attr']);
                else
                    $attrs1 = '';
                $arr['valueList'] = isset($arr['valueList']) ? $arr['valueList'] : array();
                $br = (count($arr['valueList']) > 3) ? '<br />' : '';
                if(!isset($arr['type']) || empty($arr['type']))
                    $arr['type'] = 'text';
                switch($arr['type'])
                {
                    case 'checkbox':
                        $arr['name'] .= '[]';

                        foreach($arr['valueList'] as $key => $value)
                        {
                            $arr['attr']['id'] = "{$arr['name']}_{$key}";
                            $attrs1 = self::_attrToStr($arr['attr']);
                            $checked = '';
                            if(in_array($key , $arr['selected']))
                                $checked = ' checked="checked"';
                            $inputString.=<<<EOT
							
							<input type='{$arr['type']}' name='{$arr['name']}' value='{$key}' {$attrs1}{$checked} />
							<label for='{$arr['attr']['id']}'>{$value}</label>
							{$br}
EOT;
                        }
                        break;
                    case 'radio':
                        foreach($arr['valueList'] as $key => $value)
                        {
                            $arr['attr']['id'] = "{$arr['name']}_{$key}";
                            $attrs1 = self::_attrToStr($arr['attr']);
                            $checked = '';
                            if(in_array($key , $arr['selected']))
                                $checked = ' checked="checked"';
                            $inputString.=<<<EOT
							
							<input type='{$arr['type']}' name='{$arr['name']}' value='{$key}' {$attrs1}{$checked} />
							<label for='{$arr['attr']['id']}'>{$value}</label>
							{$br}
EOT;
                        }
                        break;
                    case 'select':
                        if(is_array($arr['value']))
                            $___key__ss_ = join('_',$arr['value']);
                        else
                            $___key__ss_ = $arr['value'];
                        $arr['attr']['id'] = "{$arr['name']}_{$___key__ss_}";
                        $attrs1 = self::_attrToStr($arr['attr']);

                        $inputString.=<<<EOT
							<select name='{$arr['name']}'  {$attrs1}>
EOT;
                        foreach($arr['valueList'] as $key => $value)
                        {
                            $selected = '';
                            if(in_array($key , $arr['selected']))
                                $selected = ' selected="selected"';
                            $inputString.=<<<EOT
							
								<option value='{$key}'{$selected}>{$value}</option>
EOT;
                        }
                        $inputString.=<<<EOT
						
							</select>
EOT;
                        break;
                    case 'textarea':

                        $inputString.=<<<EOT
						
							<textarea name='{$arr['name']}' {$attrs1}>{$arr['value']}</textarea>
EOT;
                        break;
                    case 'label':

                        $inputString.=<<<EOT
						
							<label{$attrs1}>{$arr['value']}</label>
EOT;
                        break;
                    default:
                        $inputString.=<<<EOT

							<input type='{$arr['type']}' name='{$arr['name']}' value='{$arr['value']}' {$attrs1} />{$appendString}
EOT;

                }
            }
            $tbody.=<<<EOT
				<tr><td class='label'>{$arrRow['label']}</td><td>{$inputString}</td></tr>
EOT;
        }
        if($isUpload)
            $newConfig['attr']['enctype'] = 'multipart/form-data';
        $attrs = self::_attrToStr($newConfig['attr']);
        $form=<<<EOT

			<form name='{$newConfig['formName']}' action='{$newConfig['action']}' method='{$newConfig['method']}' {$attrs}>	
				{$newConfig['textTop']}
				<table>
					<thead>
						<th colspan=2>{$newConfig['title']}</th>
					</thead>
					<tbody>
						{$tbody}
						<tr>
							<td colspan=2 style="text-align:center;">
								<input type="submit" value="{$newConfig['buttonText']}" />
							</td>
						</tr>
					</tbody>
				</table>
				{$newConfig['textBottom']}
			</form>
EOT;

        return self::AppendBody($form);
    }
    private static function _attrToStr(array $attr)
    {
        $s = '';
        foreach($attr as $k => $v)
            $s.= " {$k} = '$v'";
        return $s;
    }
    /**
     * 绘制表单
     *
     */
    protected function ToForm()
    {
        $form = '';
        foreach($GLOBALS['mmHtml']['Form'] as $formName => $data)
        {
            $caption = str_replace(array('{Caption}','{Columns}',), array($data['Caption'],$data['Columns']), $data['Th']);
            if($data['Attr'])
            {
                $attrString = '';
                foreach($data['Attr'] as $key => $value)
                    $attrString .= ' '.$key.'="'.$value.'"';
            }
            else
            {
                $attrString = '';
            }
            if($data)
            {
                $form.=<<<EOT

					<form name="{$formName}"{$attrString} action="{$data['Action']}" method="{$data['Method']}">
						<table>
							<thead>
								<tr>{$caption}</tr>
							</thead>
							<tbody>	
EOT;
                foreach($data['Input'] as $inputName => $arrInput)
                {
                    $s = '';
                    if($arrInput['Type'] == 'select')
                    {
                        $s.=<<<EOT

							<select name='{$inputName}'>
EOT;
                    }

                    if($arrInput['rowAttr'])
                    {
                        $trAttrString = '';
                        foreach($arrInput['rowAttr'] as $key => $value)
                            $trAttrString .= ' '.$key.'="'.$value.'"';
                    }
                    else
                    {
                        $trAttrString = '';
                    }
                    foreach($arrInput['List'] as $iii => $input)
                    {
                        $checked = '';
                        if($arrInput['Type'] == 'checkbox')
                        {
                            preg_match("/^[^\\[]+(\\[(.+)\\])+$/" , $inputName , $aaaName);

                            if(isset($aaaName[2]))
                                $arrInputName = explode('][', $aaaName[2]);
                            else
                                $arrInputName = array();
                            if($arrInputName)
                            {
                                $arrTemp = $arrInput['Selected'];
// print_r($arrTemp);
// print_r($arrInputName);
                                $checkedFlag = true;
                                foreach($arrInputName as $temp)
                                {
                                    $temp = trim(trim($temp,'"'),"'");
                                    if(!isset($arrTemp[$temp]))
                                    {
                                        $checkedFlag = false;
                                        break;
                                    }
                                    $arrTemp = $arrTemp[$temp];
                                }
                                if($checkedFlag && $arrTemp)
                                {
                                    $checked = ' checked="checked"';
                                }
                            }
                            elseif($arrInput['Selected'] && in_array($input['Value'] , $arrInput['Selected']))
                            {
                                $checked = ' checked="checked"';
                            }

                        }
                        elseif(is_array($arrInput['Selected']) && in_array($input['Value'] , $arrInput['Selected']))
                        {
                            switch ($arrInput['Type'])
                            {
                                case 'radio':
                                case 'checkbox':
                                    $checked = ' checked="checked"';
                                    break;
                                case 'select':
                                    $checked = ' selected = "selected"';
                                    break;
                                default:
                                    $checked = '';
                            }
                        }
                        if($input['Br'])
                        {
                            if(is_numeric($input['Br']) && $iii &&  $iii % $input['Br'] == 0)
                            {
                                $br = '<br />';
                            }
                            else
                                $br = '<br />';
                        }
                        else
                        {
                            $br = '';
                        }
                        $attr = '';
                        if($input['Attr'])
                        {
                            foreach($input['Attr'] as $key => $value)
                            {
                                $attr .= " {$key} ='{$value}'";
                            }
                        }
                        if($input['Type'] == 'hidden')
                        {
                            $s.=<<<EOT

								<input type="{$input['Type']}" name="{$input['Name']}" value="{$input['Value']}" id="{$input['Name']}_{$input['Value']}" {$attr}/>
EOT;
                        }
                        elseif($input['Type'] == 'password' || $input['Type'] == 'file')
                        {
                            $s.=<<<EOT

								<input type="{$input['Type']}" name="{$input['Name']}" value="{$input['Value']}" id="{$input['Name']}_{$input['Value']}" {$attr}/>
EOT;
                        }
                        elseif($input['Type'] == 'label')
                        {
                            $t_ = $input['Label'] ? $input['Label'] : $input['Value'];
                            $s.=<<<EOT

								{$t_}
EOT;
                        }
                        elseif($input['Type'] == 'select')
                        {
                            $s.=<<<EOT

								<option name="{$input['Name']}"  value="{$input['Value']}" {$checked} {$attr}>{$input['Label']}</option>
EOT;
                        }
                        elseif($input['Type'] == 'textarea')
                        {
                            $s.=<<<EOT

								<textarea name="{$input['Name']}" {$attr}>{$input['Value']}</textarea>
EOT;
                        }
                        else
                        {
                            $s.=<<<EOT

								<input type="{$input['Type']}" name="{$input['Name']}" value="{$input['Value']}" id="{$input['Name']}_{$input['Value']}" {$checked} {$attr}/>
								<label for="{$input['Name']}_{$input['Value']}">{$input['Label']}</label>{$br}				
EOT;
                        }
                    }

                    if($arrInput['Type'] == 'hidden')
                    {
                        $form.= $s;
                    }
                    else
                    {
                        $form.= str_replace(array(
                            '{Label}',
                            '{Text}',
                            '{trAttr}',
                        ), array(
                            "\n".$arrInput['Label'],
                            "\n".$s,
                            $trAttrString,
                        ), "\n".$data['Tr']);
                    }

                    if($arrInput['Type'] == 'select')
                    {
                        $s.=<<<EOT

							</select>
EOT;
                    }
                }
                if($data['FormAppendTextBottom'])
                {
                    $form.= $data['FormAppendTextBottom'];
                }
                if(is_array($data['SumbitText']))
                {
                    $attrStr = '';
                    if(!isset($data['SumbitText']['type']))
                    {
                        $attrStr = " type='submit'";
                    }
                    foreach($data['SumbitText'] as $name => $value)
                    {
                        $attrStr.= " {$name} = '{$value}'";
                    }
                    $form.= <<<EOT
				
						<tr><td colspan={$data['Columns']} style="text-align:center;"><input {$attrStr}/></td></tr>			
EOT;
                }
                else
                    $form.= <<<EOT
					
						<tr><td colspan={$data['Columns']} style="text-align:center;"><input type="submit" value="{$data['SumbitText']}" /></td></tr>			
EOT;
                $form.=<<<EOT
				
					</tbody>
				</table>{$data['FormAppendText']}
			</form>
EOT;
            }//end if ($data)
        }//end foreach
        return $form;
    }
    /**
     * 增加反选功能js , [a#invertCheckbox]
     * 		a ) 触发此动作的标签必须跟checkbox在一个table内
     * 		b ) 触发此动作的标签id=invertCheckbox
     *
     * eg :
     * 		Html::GetHtml()->Form($formMethod, $formName ,'post','','标题<a href="#" id="invertCheckbox">反选</a>');
     * 		上面的方式会在表单头部增加一个反选按钮，它的功能就是点击后反选此表单所有checkbox
     *
     * @return Html
     */
    public function AppendInvertCheckbox()
    {
        $js=<<<EOT

			$(document).ready(function(){
				$('#invertCheckbox').click(function(){
					$('input[type=checkbox]',$(this).parents('table')).each(function(){
						$(this).attr(
							'checked',
							!$(this).attr('checked')
						);
					});
				});
			});
EOT;
        return self::AppendJavascript($js);
    }

    /**
     * 取消选择功能js , [a#cleanCheckbox]
     * 		a ) 触发此动作的标签必须跟checkbox在一个table内
     * 		b ) 触发此动作的标签id=cleanCheckbox
     *
     * eg :
     * 		Html::GetHtml()->Form($formMethod, $formName ,'post','','标题<a href="#" id="cleanCheckbox">取消选择</a>');
     * 		上面的方式会在表单头部增加一个取消选择按钮，它的功能就是点击后取消选择此表单所有checkbox
     *
     * @return Html
     */
    public function AppendCleanCheckbox()
    {
        $js=<<<EOT

			$(document).ready(function(){
				$('#cleanCheckbox').click(function(){
					$('input[type=checkbox]',$(this).parents('table')).each(function(){
						$(this).attr(
							'checked',
							false
						);
					});
				});
			});
EOT;
        return self::AppendJavascript($js);
    }
    /**
     * 绘制html
     *
     * @param boolean $isReturn	是否返回 ， true : 则直接返回需要打印的数据 , false : 直接打印数据
     * @return string
     */
    public function Show($isReturn = false)
    {
        $jqueryUrl = 'http://apps.bdimg.com/libs/jquery/2.1.4/jquery.min.js';
        $html=<<<EOT
			<html>
				<head>
					<meta http="equiv-content" content="text/html;charset=utf-8"/>
					<title>{Title}</title>
					{Meta}
					<style>
						{Css}
					</style>
					<script type="text/javascript" src="{$jqueryUrl}"></script>{JavascriptFile}
					<script type="text/javascript" language="javascript">
						{Javascript}
					</script>
				</head>
				<body>
					<div class='main'>
					{Body}{Footer}
					</div>
				</body>
			</html>
EOT;
        isset($GLOBALS['mmHtml']['Body']) || $GLOBALS['mmHtml']['Body'] = '';
        isset($GLOBALS['mmHtml']['Meta']) || $GLOBALS['mmHtml']['Meta'] = '';
        isset($GLOBALS['mmHtml']['Footer']) || $GLOBALS['mmHtml']['Footer'] = '';

        $body = $GLOBALS['mmHtml']['Body'] . self::ToForm();

        $javascriptFileText = '';
        if(isset($GLOBALS['mmHtml']['JavascriptFile']))
        {
            foreach($GLOBALS['mmHtml']['JavascriptFile'] as $src)
            {
                $javascriptFileText.=<<<EOT

				<script type="text/javascript" src="{$src}"></script>
EOT;
            }
        }
        $html = str_replace(
            array(
                '{Title}',
                '{Css}',
                '{Meta}',
                '{Javascript}',
                '{JavascriptFile}',
                '{Body}',
                '{Footer}',
            ),
            array(
                $GLOBALS['mmHtml']['Title'],
                $GLOBALS['mmHtml']['Css'],
                $GLOBALS['mmHtml']['Meta'],
                $GLOBALS['mmHtml']['Javascript'],
                $javascriptFileText,
                $body,
                $GLOBALS['mmHtml']['Footer'],
            ),
            $html
        );

        if($isReturn)
            return $html;
        echo($html);
    }

    public function _GetDefaultCss()
    {
        $css =<<<EOT
			body{font-size:14px;text-align:center;background:#fff;}
			.main{
				margin:0 auto;
				text-align:center;
			}
			table , .table{
				border-collapse:collapse;
				margin:0 auto 15px;
				width:600px;
			}
			captain{
				font-size:bold;
				text-align: center;
				width:98%;
				margin:0 auto;
				font-weight:bold;
				font-size:16px;
				height:35px;
				line-height:35px;
				background-color: #FFDEAD;
				border: 1px solid #999999;
				display:block;
			}
			th {
				font-size:bold;
				text-align: center;
				padding: 6px 6px 6px 12px;
				background-color: #EAF5F7;
				border: 1px solid #999999;
			}
			td {
				padding: 6px 6px 6px 12px;
				border:1px solid #ccc;
			}
			.err , #tips_temp{
				padding: 6px 6px 6px 12px;
				background-color: #FFDEAD;
			}
			#tips{
				color:#CD5C5C;
				font-size:12px;
			}
EOT;
        return $css;
    }
    public function InitDefaultCss()
    {
        $css = self::_GetDefaultCss();
        self::AppendCss($css);
        return $this;
    }
    public function _GetDefaultJs()
    {
        $js =<<<EOT
			\$(document).ready(function(){
				\$('tr').hover(
					function(){
						\$(this).css({'backgroundColor':'#EAF5F7'});
					},
					function()
					{
						\$(this).css({'backgroundColor':'#fff'});
					}
				);
			});
EOT;
        return $js;
    }
    public function InitDefaultJs()
    {
        $js = self::_GetDefaultJs();
        self::AppendJavascript($js);
        return $this;
    }
    /**
     * 加载验证类js
     * 		vaRe : 正则
     * 		vaTips : 提示   验证正确后的提示|验证错误的提示
     * 		vaType : 预留属性
     * 		autoValidate : 是否自动检测
     */
    public function InitValidateJs()
    {
        $js =<<<EOT
		$(document).ready(function(){
			jQuery.fn.mmValidateStart = function(){\$('.vaFalse').remove();$('.vaTrue').remove();};
			jQuery.fn.mmValidateResult = function(){return $('.vaFalse').length <= 0;};
			jQuery.fn.mmValidate = function(obj){
				if($(obj).is('textarea'))
					$(obj).attr('type','textarea');
				var type = $(obj).attr('type').toLowerCase();
				var vaRe = $(obj).attr('vaRe');
				if(!vaRe) return ;
				if(!/^\/.*\/.*/.test(vaRe))
					vaRe = "/"+vaRe+"/";
				var vaTips = $(obj).attr('vaTips');
				var vaType = $(obj).attr('vaType');
				var name = $(obj).attr('name').replace('[','___').replace(']','__');
				
				if(!vaTips)
					vaTips = 'true|false';
				var arrTips = vaTips.split('|');
				if(arrTips.length < 2)
					arrTips[1] = 'false';
				tips = '';
				c = 'vaTrue';
				if(/text|textarea|hidden/.test(type))
				{
					var val = $(obj).val();
					if(vaRe)
					{
						if(vaRe.test(val))
						{
							tips = arrTips[0];
						}
						else
						{
							c = 'vaFalse';
							tips = arrTips[1];
						}
					}
				}
				$('<span />' , {id:'re'+name,'class':c}).html(tips).appentTo($(obj).parent());
			};
			$('form').submit(function(){
				$.mmValidateStart();
				$('input' , this).each(function(){\$.mmValidate(this);});
				return $.mmValidateResult();
			});
			$('input[autoValidate=1]').each(function(){\$.mmValidate(this);});
			$('textarea[autoValidate=1]').each(function(){\$.mmValidate(this);});
		});
EOT;
        return self::AppendJavascript($js);
    }
    public function InitAjaxSubmit()
    {
        $js =<<<EOT
			\$(document).ready(function(){
				\$('form.ajaxForm').submit(function(){
					
					\$.ajax({
						type : \$(this).attr('method'),
						url : \$(this).attr('action'),
						cache : false,
						data : \$(this).serialize(),
						error : function(){
							alert('提交失败!');
						},
						success : function(data)
						{
							if(data == true || data == 1 || data == '1')
								alert('提交成功!');
							else
								alert('提交失败'+data);
						}
					});
					return false;
				});	
			});	
EOT;
        return self::AppendJavascript($js);
    }
    public function InitAjaxDelete()
    {
        $js =<<<EOT
			\$(document).ready(function(){
				\$('a.ajaxDelete').click(function(){
					if(confirm('是否删除?'))
					{
						\$(this).attr('id' , '__ajaxDeleteId__');
						\$.ajax({
							url : \$(this).attr('href'),
							cache : false,
							error : function(){
								alert('提交失败!');
							},
							success : function(data)
							{
								if(data == true || data == 1 || data == '1')
								{
									$('a#__ajaxDeleteId__').parents('tr').css({'backgroundColor':'#ffcccc'}).animate({
										'opacity':'0'
									},2000 , function(){\$(this).remove();});
								}
								else
									alert('提交失败'+data);
							}
						});
					}
					return false;
				});	
			});
EOT;
        return self::AppendJavascript($js);
    }
    public function appendMeta($name  , $content , $nameKey = 'name')
    {
        $GLOBALS['mmHtml']['Meta'] .= <<<EOT

		<meta {$nameKey}="{$name}" content="{$content}" />
EOT;
    }
    public function clear()
    {
        $GLOBALS['mmHtml'] = array(
            'Css'			=>	'',
            'Meta'			=>	'',
            'Javascript'	=>	'',
            'Body'			=>	'',
            'Footer'		=>	'',
            'Title'			=>	'',
            'Form'			=>	array(),
        );
    }

    /**
     * 等待跳转
     *
     * @param string $url
     * @param string $title
     * @param string $content			如果此参数不为null，则覆盖所有内容，需要自己完成对应的内容处理
     * @param integer $time
     * @param strint $appendContent
     * @return void
     */
    public function WaitingToUrl($url , $title = null , $content = null , $time = 5 , $appendContent = '')
    {
        self::clear();
        self::appendMeta("refresh", "{$time};url={$url}", "http-equiv");
        if (is_null($content)) {
            $js = <<<EOT
			$(document).ready(function(){
				wtLoading();
			});
			function wtLoading()
			{
				unit = 1000;
				count = {$time} * 1000 / unit;
				width = $('#percent').width();
				warpWidth = $('#percent').parent().width();
				nowWidth = width + 300 / count;
				
				if(nowWidth >= warpWidth)
				{
					$('#percent').text('100%');
					window.location = "{$url}";
				}
				else
				{
					percent = parseInt((nowWidth / warpWidth) * 100);
					$('#percent').text(percent + '%').animate({
						'width':nowWidth
					}, unit);
					setTimeout('wtLoading()',unit);
				}
			}
EOT;
            $css = <<<EOT
				#loading{
					width:302px;
					height:18px;
					line-height:18px;
					border:1px solid #999999;
					margin:0 auto;
				}
				#percent{
					width:1px;
					margin:1px;
					height:16px;
					line-height:16px;
					background-color:#EAF5F7;
					text-align:right;
				}
				#loadingTips{
					
				}
				#loadingTips a{
					text-decoration:none;
					font-weight:bold;
				}
EOT;
            self::AppendCss($css)->InitDefaultCss();
            self::AppendJavascript($js);
            if (isset($title)) {
                $css = <<<EOT
					h1{
						font-size:14px;
						height:30px;
						line-height:30px;
						text-align:center;
						margin:0 auto;
					}			
EOT;
                self::AppendCss($css);
                $content = <<<EOT
<div class="main">
	<h1>{$title}</h1>
	<div id='loading'>
		<div id='percent'></div>
	</div>
	<p id='loadingTips'> 
		如果您的浏览器不支持跳转,
		<a href="{$url}">请点这里</a>.
	</p>
	{$appendContent}
</div>
EOT;
            } else {
                $content = <<<EOT
<div class="main">
	<div id='loading'>
		<div id='percent'></div>
	</div>
	<p id='loadingTips'> 
		如果您的浏览器不支持跳转,
		<a href="{$url}">请点这里</a>.
	</p>
	{$appendContent}
</div>
EOT;

            }
        }
        if (isset($title)) {
            self::Title($title);

        }
        self::AppendBody($content);
        self::Show();
        exit(0);
    }
}