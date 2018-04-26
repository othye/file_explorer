

<pre>
<?php

function sortRows($data)
{
	$size = count($data);

	for ($i = 0; $i < $size; ++$i) {
		$row_num = findSmallest($i, $size, $data);
		$tmp = $data[$row_num];
		$data[$row_num] = $data[$i];
		$data[$i] = $tmp;
	}

	return ( $data );
}

function findSmallest($i, $end, $data)
{
	$min['pos'] = $i;
	$min['value'] = $data[$i]['data'];
	$min['dir'] = $data[$i]['dir'];
	for (; $i < $end; ++$i) {
		if ($data[$i]['dir']) {
			if ($min['dir']) {
				if ($data[$i]['data'] < $min['value']) {
					$min['value'] = $data[$i]['data'];
					$min['dir'] = $data[$i]['dir'];
					$min['pos'] = $i;
				}
			} else {
				$min['value'] = $data[$i]['data'];
				$min['dir'] = $data[$i]['dir'];
				$min['pos'] = $i;
			}
		} else {
			if (!$min['dir'] && $data[$i]['data'] < $min['value']) {
				$min['value'] = $data[$i]['data'];
				$min['dir'] = $data[$i]['dir'];
				$min['pos'] = $i;
			}
		}
	}
	return ( $min['pos'] );
}

	$self = $_SERVER['PHP_SELF'];
	if (isset($_GET['dir'])) {
		$dir = $_GET['dir'];
		$size = strlen($dir);
		while ($dir[$size - 1] == '/') {
			$dir = substr($dir, 0, $size - 1);
			$size = strlen($dir);
		}
	} else {
		$dir = $_SERVER["SCRIPT_FILENAME"];
		$size = strlen($dir);
		while ($dir[$size - 1] != '/') {
			$dir = substr($dir, 0, $size - 1);
			$size = strlen($dir);
		}
		$dir = substr($dir, 0, $size - 1);
	}

	echo "DIR : ", $dir;
	echo "\n\n";
	if (is_dir($dir)) {
		if ($handle = opendir($dir)) {
			$size_document_root = strlen($_SERVER['DOCUMENT_ROOT']);
			$pos = strrpos($dir, "/");
			$topdir = substr($dir, 0, $pos + 1);
			$i = 0;
  	  		while (false !== ($file = readdir($handle))) {
        		if ($file != "." && $file != "..") {
					$rows[$i]['data'] = $file;
					$rows[$i]['dir'] = is_dir($dir . "/" . $file);
					$i++;
				}
			}
    		closedir($handle);
		}

		$size = count($rows);
		$rows = sortRows($rows);
		echo "<table style=\"white-space:pre;\">";
		for ($i = 0; $i < $size; ++$i) {
			$topdir = $dir . "/" . $rows[$i]['data'];
			echo "<tr>";
			echo "<td>";
			if ($rows[$i]['dir']) {
				echo "[DIR]";
				$file_type = "dir";
			} else {
				echo "[FILE]";
				$file_type = "file";
			}
			echo "</td>";
			echo "<td>    ";
        	echo "<a href='", $self, "?dir=", $topdir, "'>", $rows[$i]['data'], "</a>\n";
			echo "</td>";
			echo "<td>    ";
          	echo "<a href='", substr($topdir, $size_document_root,  strlen($topdir) - $size_document_root), "'>open ", $file_type, "</a>\n";
			echo "</td>";
			echo "</tr>";
        }
		echo "</table>";
	}
?>
</pre>
