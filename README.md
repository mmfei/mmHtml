# a html code rander 
### author mmfei(wlfkongl@163.com)

## add a html code
```
		$htmlText = "Your html code";
        $css = <<<EOT
			.tableInfo{
				width:30%;
				float:left;
				border:1px solid #0066B3;
				margin:10px;
			}
			.tableInfo dt{
				height:30px;
				line-height:30px;
				background:#80C8FE;
			}
			.tableInfo dt span{
				background:#80C8FE;
			}
			.tableInfo dd{
				text-align:left;
				margin:0 1px;
				color:#333;
			}
			.tableInfo dd span{
				color:#aaa;
				text-align:right;
			}
			dd:hover{
				background:#BFE3FE
			}
EOT;
        $js = <<<EOT
			$(document).ready(function(){
				$('dl').each(function(){
					$(this).css({'border':'1px solid #000'});
					$(this).find('dd:even').css('background':'#80C8FE');
				});
			});
EOT;
        MmHtml::GetHtml()->AppendCss($css)->AppendJavascript($js);
        MmHtml::GetHtml()->InitDefaultCss()->InitDefaultJs()->AppendBody($htmlText)->Show();
```


## add a form

```

        $json = MmHtml::PG("json");

        $config=array(
            'formName' => 'form1',
            'method' => 'post',
            'action' => '',
            'title' => 'Html page's title',
            'attr' => array(),//form attrs , if you need upload some files  ， append: enctype=>"multipart/form-data"
            'textTop' => '',//print at the top of form
            'textBottom' => '',//print at the bottom of form
            'buttonText' => 'submit',//text of submit button
            'inputList' => array(//form input items list
            )
        );
        $config['inputList'][] = array(
            "label"=>"json",
            "list"=>array(
                array(
                    "name"=>"json",
                    "type"=>"textarea",
                    "value"=>$json,
                    "attr"=>array('rows'=>"10","cols"=>'100'),
                    "selected"=>array(),
                    "valueList"=>array("value"=>"",),
                )
            )
        );
        MmHtml::GetHtml()->FormByConfig($config)->InitDefaultCss()->InitDefaultJs()->Show();

        if($json){
            try {
				//Do someting

            } catch (\Exception $e){

                print_r($e);

                exit;
            }
        }
```
