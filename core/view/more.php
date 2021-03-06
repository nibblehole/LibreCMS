<?php
/**
 * LibreCMS - Copyright (C) Diemen Design 2019
 *
 * View - More Renderer - For Loading More In-Page Content Items
 *
 * more.php version 2.0.1
 *
 * LICENSE: This source file may be modifired and distributed under the terms of
 * the MIT license that is available through the world-wide-web at the following
 * URI: http://opensource.org/licenses/MIT.  If you did not receive a copy of
 * the MIT License and are unable to obtain it through the web, please
 * check the root folder of the project for a copy.
 *
 * @category   Administration - View - More
 * @package    core/view/more.php
 * @author     Dennis Suitters <dennis@diemen.design>
 * @copyright  2014-2019 Diemen Design
 * @license    http://opensource.org/licenses/MIT  MIT License
 * @version    2.0.1
 * @link       https://github.com/DiemenDesign/LibreCMS
 * @notes      This PHP Script is designed to be executed using PHP 7+
 * @changes    v2.0.1 Add Sluggification
 */
$getcfg=true;
if(!defined('DS'))define('DS',DIRECTORY_SEPARATOR);
require'..'.DS.'..'.DS.'core'.DS.'db.php';
define('SESSIONID',session_id());
define('THEME','layout'.DS.$config['theme']);
define('URL',PROTOCOL.$_SERVER['HTTP_HOST'].$settings['system']['url'].'/');
$contentType=isset($_POST['c'])?filter_input(INPUT_POST,'c',FILTER_SANITIZE_STRING):filter_input(INPUT_GET,'c',FILTER_SANITIZE_STRING);
$view=isset($_POST['v'])?filter_input(INPUT_POST,'v',FILTER_SANITIZE_STRING):filter_input(INPUT_GET,'v',FILTER_SANITIZE_STRING);
$show='categories';
$i=isset($_POST['i'])?filter_input(INPUT_POST,'i',FILTER_SANITIZE_NUMBER_INT):filter_input(INPUT_GET,'i',FILTER_SANITIZE_NUMBER_INT);
$html=file_exists('..'.DS.'..'.DS.'layout'.DS.$config['theme'].DS.$view.'.html')?file_get_contents('..'.DS.'..'.DS.'layout'.DS.$config['theme'].DS.$view.'.html'):file_get_contents('..'.DS.'..'.DS.'layout'.DS.$config['theme'].DS.'content.html');
$itemCount=$config['showItems'];
$s=$db->prepare("SELECT * FROM `".$prefix."content` WHERE contentType LIKE :contentType AND status LIKE :status AND internal!='1' AND pti < :ti ORDER BY ti DESC LIMIT $i,$itemCount");
$s->execute([
  ':contentType'=>$contentType,
  ':status'=>'published',
  ':ti'=>time()
]);
if(stristr($html,'<more')){
  preg_match('/<more>([\w\W]*?)<\/more>/',$html,$matches);
  $more=$matches[1];
  $more=preg_replace([
    '/<print view>/',
    '/<print contentType>/',
    '/<print config=[\"\']?showItems[\"\']?>/',
  ],[
    $view,
    $contentType,
    $itemCount+$i
  ],$more);
}else
  $more='';
if($s->rowCount()<=$itemCount)$more='';
if(stristr($html,'<items>')){
  preg_match('/<items>([\w\W]*?)<\/items>/',$html,$matches);
  $item=$matches[1];
  $output='';
  $si=1;
  while($r=$s->fetch(PDO::FETCH_ASSOC)){
    $items=$item;
    $contentType=$r['contentType'];
    if($si==1){
      $filechk=basename($r['file']);
      $thumbchk=basename($r['thumb']);
      if($r['file']!=''&&file_exists('media'.DS.$filechk))
        $shareImage=$r['file'];
      elseif($r['thumb']!=''&&file_exists('media'.DS.$thumbchk))
        $shareImage=$r['thumb'];
      $si++;
    }
    if(preg_match('/<print content=[\"\']?thumb[\"\']?>/',$items)){
      $r['thumb']=str_replace(URL,'',$r['thumb']);
      $items=$r['thumb']?preg_replace('/<print content=[\"\']?thumb[\"\']?>/',$r['thumb'],$items):preg_replace('/<print content=[\"\']?thumb[\"\']?>/','layout'.DS.$config['theme'].DS.'images'.DS.'noimage.jpg',$items);
    }
    $items=preg_replace('/<print content=[\"\']?alttitle[\"\']?>/',$r['title'],$items);
    $r['notes']=strip_tags($r['notes']);
    if($r['contentType']=='testimonials'||$r['contentType']=='testimonial'){
      if(stristr($items,'<controls>'))
        $items=preg_replace('~<controls>.*?<\/controls>~is','',$items,1);
      $controls='';
    }else{
      if(stristr($items,'<view>')){
        $items=preg_replace([
          '/<print content=[\"\']?linktitle[\"\']?>/',
          '/<print content=[\"\']?title[\"\']?>/',
          '/<view>/',
          '/<\/view>/'
        ],[
          URL.$r['contentType'].'/'.$r['urlSlug'],
          $r['title'],
          '',
          ''
        ],$items);
      }
    if($r['contentType']=='service'){
      if($r['bookable']==1){
        if(stristr($items,'<service>')){
          $items=preg_replace([
            '/<print content=[\"\']?bookservice[\"\']?>/',
            '/<service>/',
            '/<\/service>/',
            '~<inventory>.*?<\/inventory>~is'
          ],[
            URL.'bookings/'.$r['id'],
            '',
            '',
            ''
          ],$items);
        }
      }else
        $items=preg_replace('~<service.*?>.*?<\/service>~is','',$items,1);
    }else
      $items=preg_replace('~<service>.*?<\/service>~is','',$items,1);
    if($r['contentType']=='inventory'&&is_numeric($r['cost'])){
      if(stristr($items,'<inventory>')){
        $items=preg_replace([
          '/<inventory>/',
          '/<\/inventory>/',
          '~<service>.*?<\/service>~is'
        ],'',$items);
      }elseif(stristr($items,'<inventory>')&&$r['contentType']!='inventory'&&!is_numeric($r['cost']))
        $items=preg_replace('~<inventory>.*?<\/inventory>~is','',$items,1);
    }else
      $items=preg_replace('~<inventory>.*?<\/inventory>~is','',$items,1);
    $items=str_replace([
      '<controls>',
      '</controls>'
    ],'',$items);
  }
  require'..'.DS.'parser.php';
  $output.=$items;
}
$html=$output;
}
print$html.$more;
