<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<style type="text/css">
		body, td {
			font-size: 8pt;
			font-family: sans-serif;
			background:#f5f5f5;
			font-size:12px;
			font-family:Tahoma;
		}
		a:link, a:hover, a:active, a:visited {
			font-size: 8pt;
			color: #0000FF;
		}
		.baslik {
		text-decoration:underline;
		font-weight:bold;
		}
		</style>
	</head>
	<body>
		<?php

		$dir = ".";
		$directories = array();
		$files = array();

		function recursedir($rootdir){
			$directories = array();
			$files = array();
			$dir = (substr($rootdir, -1) == '/') ? substr($rootdir, 0, -1) : $rootdir;
			if(is_dir($dir)){
				if($handle = opendir($dir)){
					while(false !== ($file = readdir($handle))){
						if($file != "." && $file != ".."){
							$filename = $dir.'/'.$file;
							if(is_dir($filename)){
								$folder = $filename;
								$files = array_merge($files, recursedir($filename));
								//echo $folder."<br />";
							} else {
								$files[$filename] = filemtime($filename);
							}
						}
					}
					closedir($handle);
				} else {
					die('Dizin a&#231;&#305;lamad&#305;.');
				}
			} else {
				die('Ge&#231;ersiz dizin.');
			}
			return $files;
		}

		$files = recursedir(".");
		if($_GET['sort'] == 'alpha'){
			if($_GET['mode'] == 'desc'){
				krsort($files);
				$highlight = 'alpha_desc';
			} else {
				ksort($files);
				$highlight = 'alpha_asc';
			}
		} else {
			if($_GET['mode'] == 'asc'){
				asort($files, SORT_NUMERIC);
				$highlight = 'date_asc';
			} else {
				arsort($files, SORT_NUMERIC);
				$highlight = 'date_desc';
			}
		}
		$sort_alpha_asc = ($highlight == 'alpha_asc') ? '<b>Artan</b>' : '<a href="?sort=alpha&mode=asc">Artan</a>';
		$sort_alpha_desc = ($highlight == 'alpha_desc') ? '<b>Azalan</b>' : '<a href="?sort=alpha&mode=desc">Azalan</a>';
		$sort_date_asc = ($highlight == 'date_asc') ? '<b>Artan</b>' : '<a href="?sort=date&mode=asc">Artan</a>';
		$sort_date_desc = ($highlight == 'date_desc') ? '<b>Azalan</b>' : '<a href="?sort=date&mode=desc">Azalan</a>';
		echo "<b>S&#305;ralama &#351;ekli:</b> <br />Dosya Tarihi - $sort_date_asc | $sort_date_desc <br />Dosya Ad&#305; - $sort_alpha_asc | $sort_alpha_desc<br />\n<br />\n";

		echo "<table border=\"0\" width=\"90%\" style=\"margin:0 auto;\">\n<tr><td colspan=\"3\"><span class=\"baslik\">&#214;nemli:</span><br />+ Bu dosyay&#305; kullanmadan evvel dizinlerde ne zaman g&#252;ncelleme yapt&#305;&#287;&#305;n&#305;z&#305; bilmeniz gerekir. Dosya size sadece, yerle&#351;tirildi&#287;i dizin ve alt dizinlerindeki dosyalar&#305;n son d&#252;zenlenme tarihini verir. <br />+ <b><i>cache ve error</i></b> gibi dosya ve klas&#246;rler sistem gere&#287;i g&#252;ncellenmi&#351; olabilir. G&#252;ncellenmi&#351; bir dosyay&#305; ftp &#252;zerinden a&#231;arak kotrol etmek istedi&#287;inizde herhengi bir i&#351;lem yapmazsan&#305;z kaydetmeden kapat&#305;n, g&#252;ncelleme tarihi de&#287;i&#351;mesin, akl&#305;n&#305;z&#305; kar&#305;&#351;t&#305;rmas&#305;n. <br />+ Bu listeye bakarak d&#252;zenleme yapt&#305;&#287;&#305;n&#305;z dosyalar&#305; bir kenara not etmeyi unutmay&#305;n; zira i&#351;lem yapt&#305;&#287;&#305;n&#305;z dosyalar&#305; bilmeye ihtiyac&#305;n&#305;z olabilir.<br />+ &#304;&#351;iniz bitti&#287;inde dosyay&#305; dizinden kald&#305;r&#305;n.</p><p>&nbsp;</p></td></tr><tr><td><span class=\"baslik\">Dosya</span></td><td width=\"25\"></td><td><span class=\"baslik\">Boyut</span></td><td width=\"25\"></td><td width=\"150\"><span class=\"baslik\">Son G&#252;ncelleme</span></td></tr>\n";
		foreach($files as $file => $timestamp){
			echo "<tr><td><a href=\"$dir/$file\">$file</a></td><td></td><td>";
			$filesize = filesize($file);
			if($filesize >= 1048576){
				echo round($filesize / 1048576, 1).'MB';
			} else {
				echo round($filesize / 1024, 1).'kb';
			}
			echo '</td><td></td><td>'.date('d M Y H:i:s', $timestamp)."</td></tr>\n";
		}
		echo '</table>';

		?>
	</body>
</html>
