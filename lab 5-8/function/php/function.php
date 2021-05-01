<?php
	function EditBD()
	{
		if(isset($_GET["ID"]))
		{
			$articleName = htmlspecialchars($_GET["Heading"]);
			$textiq = htmlspecialchars($_GET["MainText"]);
			$imageURLs = htmlspecialchars($_GET["UrlImg"]);
			$IDarticle = htmlspecialchars($_GET["ID"]);
			Connect();
			global $link;
			$sql = "UPDATE article SET Nomen='$articleName',Textiq='$textiq',ImageURL='$imageURLs' WHERE IDarticle=$IDarticle";
			$link->query($sql);
			Close();
			OnlyIDPeople();
		}
		else if (isset($_GET["Del"]))
		{
			$IDarticle = htmlspecialchars($_GET["Del"]);
			Connect();
			global $link;
			$sql = "DELETE FROM article WHERE IDarticle='$IDarticle'";
			$link->query($sql);
			Close();
			OnlyIDPeople();
		}
		else if(isset($_GET["Heading"]) and isset($_GET["MainText"]) and isset($_GET["UrlImg"]))
		{
			$articleName = htmlspecialchars($_GET["Heading"]);
			$textiq = htmlspecialchars($_GET["MainText"]);
			$imageURLs = htmlspecialchars($_GET["UrlImg"]);
			Connect();
			global $link;
			$sql = "INSERT INTO article (Nomen, Textiq, ImageURL) VALUES ('$articleName', '$textiq', '$imageURLs')";
			$link->query($sql);
			Close();
			OnlyIDPeople();
		}
		if(isset($_GET["IDP"]))
		{
			echo '
			<input type="hidden" name="IDP" value="';echo htmlspecialchars($_GET['IDP']);echo' >';
		}
		else if (isset($_GET["pswrd"]) and isset($_GET["lg"]) and htmlspecialchars($_GET["pswrd"])!='' and htmlspecialchars($_GET["lg"])!=''and isset($_GET["next"]))
		{
			global $link;
			global $result;
			$lg = htmlspecialchars($_GET["lg"]);
			$password = htmlspecialchars($_GET["pswrd"]);
			Connect();
			$sql = "SELECT * FROM `accounts` WHERE `Name` = '$lg'";
			$result = mysqli_query($link, $sql) or die("Ошибка " . mysqli_error($link));
			$row_count = mysqli_num_rows($result);

			if($row_count = 0)
			{
				$sql = "INSERT INTO accounts (role, login,password ) VALUES (0,'$lg', '$password')";
				if ($link->query($sql) === TRUE) {
				  echo "New record created successfully";
				} else {
				  echo "Error: " . $sql . "<br>" . $link->error;
				}
			}
			$rows = mysqli_num_rows($result);
			for($i=1;$i<=$rows;$i++)
			{
				$row = mysqli_fetch_row($result);
				if($row[0]==$lg)
				{
					break;
				}
			}
			$url = strtok(GetURL(), '?');
			$url .='?IDP='.$row[0];
			if(isset($_GET["News"]))
			{
				$url.='&News=5';
			}
			else if(isset($_GET["Counter"]))
			{
				$url.='&Counter=';
			}
			else if(isset($_GET["Download"]))
			{
				$url.='&Download=';
			}
			else if (isset($_GET["Edit"])) {
				$url.='&Edit='.htmlspecialchars($_GET["Edit"]);
			}
			else if (isset($_GET["Add"])) {
				$url.='&Add=';
			}
			else if (isset($_GET["Del"])) {
				$url.='&Del='.htmlspecialchars($_GET["Del"]);
			}
			echo $url;
			header ('Location: '.$url);
			Close();
		}
	}

	function OnlyIDPeople()
	{
		$url = strtok(GetURL(), '?');
		if(isset($_GET["IDP"]))
		{
			$IDP = htmlspecialchars($_GET["IDP"]);
			$url .='?IDP='.$IDP;
			if(isset($_GET["News"]))
			{
				$url.='&News=';
			}
			else if(isset($_GET["Counter"]))
			{
				$url.='&Counter=';
			}
			else if(isset($_GET["Download"]))
			{
				$url.='&Download=';
			}
			else if(isset($_GET["Del"]))
			{
				$url.='&News=';
			}
		}
		else
		{
			if(isset($_GET["News"]))
			{
				$url.='?News=';
			}
			else if(isset($_GET["Counter"]))
			{
				$url.='?Counter=';
			}
			else if(isset($_GET["Download"]))
			{
				$url.='?Download=';
			}
			else if(isset($_GET["Del"]))
			{
				$url.='?News=2';
			}
		}


		header ('Location: '.$url);
	}

	function GetURL()
	{
	    if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
	         $url = "https://";
	    else
	         $url = "http://";
	    $url.= $_SERVER['HTTP_HOST'];
	    $url.= $_SERVER['REQUEST_URI'];
	    return $url;
	}

	function ShowAllArticles()
	{
		global $result;
		Connect();
		SelectBD("article");
		$rows = mysqli_num_rows($result);
		$articleWithStyle;
		echo "
		<form>
			<a name='Mid' class='anchor'></a>
			<br><button class='buttonArticle' name = 'Add'>Добавить</button>
		</form>
		";
		for ($i=$rows; $i > 0; $i--)
		{
			$articleWithStyle = "<br><div class = 'articleName'>";
			$row = mysqli_fetch_row($result);
			$articleWithStyle.= $row[1];
			$articleWithStyle.="</div><br>";
			echo $articleWithStyle;
			$articleWithStyle = "<div class = 'text'>";
			$articleWithStyle.=$row[2];
			$articleWithStyle.="</div><br>";
			echo $articleWithStyle;
			if ($row[3]!=='')
			{
				$articleWithStyle = "<img src='";
				$articleWithStyle.=$row[3];
				$articleWithStyle.="'>";
				echo $articleWithStyle;
			}
			echo "
			<form>
				<br><button class='buttonArticle' name = 'Edit' value = '$row[0]'>Редактировать</button>
				<button class='buttonArticle' name = 'Del' value = '$row[0]'>Удалить</button>
			</form>";
			echo "<br><br>";

		}
		Close();
	}

	function SearchArticle($IDarticle,$nameBD)
	{
		Connect();
		global $link,$result;
		$query ="SELECT * FROM" . $nameBD;
		$result = mysqli_query($link, $query) or die("Ошибка " . mysqli_error($link));
		Close();
		global $result;
		$rows = mysqli_num_rows($result);
		for($i=1;$i<=$rows;$i++)
		{
			$row = mysqli_fetch_row($result);
			if($row[0]==$IDarticle)
			{
				return $row;
			}
		}
	}


	function GetString($num,$rows)
	{
		global $result;
		for($i=1;$i<=$rows;$i++)
		{
			$row = mysqli_fetch_row($result);
			if($i==$num)
			{
				return $row;
			}
		}
	}

	function SearchByMenuItem($idMenu,$nameBD)
	{
		Connect();
		SelectBD($nameBD);
		Close();
		global $result;
		$rows = mysqli_num_rows($result);
		for($i=1;$i<=$rows;$i++)
		{
			$row = mysqli_fetch_row($result);
			if($row[0]==$idMenu)
			{
				return $row[1];
			}
		}
	}

	function SearchByElemente($idMenu,$nameBD,$column)
	{
		Connect();
		SelectBD($nameBD);
		Close();
		global $result;
		$rows = mysqli_num_rows($result);
		for($i=1;$i<=$rows;$i++)
		{
			$row = mysqli_fetch_row($result);
			if($row[0]==$idMenu)
			{
				return $row[$column];
			}
		}
	}

	function SelectBD($nameTable)
	{
		global $link,$result;
		$query ="SELECT * FROM " . $nameTable . " ORDER BY -IDarticle";
		$result = mysqli_query($link, $query) or die("Ошибка " . mysqli_error($link));
	}

	function Head($idIM)
	{
		$idHead=SearchByMenuItem($idIM,"`head_пункт_меню`");
		global $headstr;
		$headstr = "<meta charset=\"";
		$headstr.=SearchByElemente($idHead,"`head`",1);
		$headstr.="\"><title>";
		$headstr.=SearchByElemente($idHead,"`head`",2);
		$headstr.="</title>";
		$headstr.=SearchByElemente($idHead,"`head`",3);
	}

	function Menu($idIM)
	{
		$idHead=SearchByMenuItem($idIM,"`пункт_навигации_пункт_меню`");
		global $menustr;
		$menustr=SearchByElemente($idHead,"`пункт_навигации`",1);
	}

	function SiteHeader($idIM)
	{
		$idHead=SearchByMenuItem($idIM,"`header_footer_menu_item`");
		global $headerstr;
		$headerstr=SearchByElemente($idHead,"`header_footer`",1);
	}

	function Artical($idIM)
	{
		$idHead=SearchByMenuItem($idIM,"`статья_пункт_меню`");
		global $articalstr;
		$articalstr=SearchByElemente($idHead,"`article`",1);
	}

	function Footer($idIM)
	{
		$idHead=SearchByMenuItem($idIM,"`header_footer_menu_item`");
		global $footerstr;
		$footerstr=SearchByElemente($idHead,"`header_footer`",2);
	}

	function SelectItemMenu($idIM)
	{
		global $result;
		global $itemMenu;
		global $menubarstr;
		$rows = mysqli_num_rows($result);
	    $row = GetString($idIM,$rows);
	    Head($row[0]);
	    SiteHeader($row[0]);
	    Menu($row[0]);
	    Artical($row[0]);
	    Footer($row[0]);
	    $itemMenu=$row[0];
		$menubarstr=$row[1];
		mysqli_free_result($result);
	}

	function ReactToPressedButton()
	{
		Connect();
		SelectBD("`menu item`");
		Close();
		if(isset($_GET['AboutGame']) or empty($_GET))
		{
			SelectItemMenu(1);
		}
		else if(isset($_GET['Counter']))
		{
			SelectItemMenu(2);
		}
		else if(isset($_GET['Download']))
		{
			SelectItemMenu(3);
		}
	}
?>
