<?php
/*
MYSQL ���ݿ���ʷ�װ��
MYSQL ���ݷ��ʷ�ʽ��php4֧����mysql_��ͷ�Ĺ��̷��ʷ�ʽ��php5��ʼ֧����mysqli_��ͷ�Ĺ��̺�mysqli�������
���ʷ�ʽ������װ����mysql_��װ
 
���ݷ��ʵ�һ�����̣�
1,�������ݿ� mysql_connect or mysql_pconnect
2,ѡ�����ݿ� mysql_select_db
3,ִ��SQL��ѯ mysql_query
4,�����ص����� mysql_fetch_array mysql_num_rows mysql_fetch_assoc mysql_fetch_row etc
*/
class db_mysql
{
  var $querynum = 0 ; //��ǰҳ����̲�ѯ���ݿ�Ĵ���
  var $dblink ;       //���ݿ�������Դ
  
  //�������ݿ�
  function connect($dbhost,$dbuser,$dbpw,$dbname='',$dbcharset='utf-8',$pconnect=0 , $halt=true)
  {
     $func = empty($pconnect) ? 'mysql_connect' : 'mysql_pconnect' ;
     $this->dblink = @$func($dbhost,$dbuser,$dbpw) ;
     if ($halt && !$this->dblink)
     {
        $this->halt("�޷��������ݿ⣡" . mysql_error() );
     }
     
     //���ò�ѯ�ַ���
     mysql_query("SET character_set_connection={$dbcharset},character_set_results={$dbcharset},character_set_client=binary",$this->dblink) ;
     
     //ѡ�����ݿ�
     $dbname && @mysql_select_db($dbname,$this->dblink) ;
  }
  
  //ѡ�����ݿ�
  function select_db($dbname)
  {
     return mysql_select_db($dbname,$this->dblink);
  }
  
  //ִ��SQL��ѯ
  function query($sql)
  {
     $this->querynum++ ;
     return mysql_query($sql,$this->dblink) ;
  }
  
  //�������һ�������Ӿ��������INSERT��UPDATE ��DELETE ��ѯ��Ӱ��ļ�¼����
  function affected_rows()
  {
     return mysql_affected_rows($this->dblink) ;
  }
  
  //ȡ�ý�������е���Ŀ,ֻ��select��ѯ�Ľ������Ч
  function num_rows($result)
  {
     return mysql_num_rows($result) ;
  }
  
  //��õ���Ĳ�ѯ���
  function result($result,$row=0)
  {
     return mysql_result($result,$row) ;
  }
  
  //ȡ����һ�� INSERT ���������� ID,ֻ�Ա���AUTO_INCREMENT ID�Ĳ�����Ч
  function insert_id()
  {
     return ($id = mysql_insert_id($this->dblink)) >= 0 ? $id : $this->result($this->query("SELECT last_insert_id()"), 0);
  }
  
  //�ӽ������ȡ��ǰ�У�������Ϊkey��ʾ�Ĺ���������ʽ����
  function fetch_row($result)
  {
     return mysql_fetch_row($result) ;
  }
  
  //�ӽ������ȡ��ǰ�У����ֶ���Ϊkey��ʾ�Ĺ���������ʽ����
  function fetch_assoc($result)
  {
     return mysql_fetch_assoc($result);
  }
  
  //�ӽ������ȡ��ǰ�У����ֶ���������Ϊkey��ʾ�Ĺ���������ʽ����
  function fetch_array($result)
  {
     return mysql_fetch_array($result);
  }
  
  //�ر�����
  function close()
  {
     return mysql_close($this->dblink) ;
  }
  
  //����򵥵Ĵ���html��ʾ��Ϣ����ֹ����
  function halt($msg)
  {
     $message = "<html>\n<head>\n" ;
     $message .= "<meta content='text/html;charset=gb2312'>\n" ;
     $message .= "</head>\n" ;
     $message .= "<body>\n" ;
     $message .= "���ݿ����".htmlspecialchars($msg)."\n" ;
     $message .= "</body>\n" ;
     $message .= "</html>" ;
     
     echo $message ;
     exit ;
  }
}
?>
