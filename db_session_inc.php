<?php
    class DbSessionHandler implements SessionHandlerInterface  {
    
        //Global variable used for database connection
        private $db = NULL;
        
        // This class contaians the following attribute
        private $savePath;
        
        /* 
         * defining method open
         * takes two arguments
         * $savePath
         * $sessionName
         */
        public function open($savePath, $sessionName)  {
            
            //specify database parameters to create a connection
            $user = 'XXXX'; 
            $pass = 'XXXX';
            $dbname = 'XXXX';
            $host = '127.0.0.1';          
            
            try  {
                // create a db connection
                $this->db = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
            }catch(PDOException $e){
                print "Error: " . $e->getMessage() . '</br>';
                die(); 
            }//End of try-catch block
     
            return true;
        }// End of open function

    
        /*
         * defining method close
         * takes no arguments
         */
        public function close()    {
            // destroy db connection
            $this->db = NULL;
            return true;
        }// End of close function

        
        /*
         * defining method read
         * Takes one arfument
         * $id string
         */
        public function read($id)    {
            // prepare sql query
            $stmt = $this->db->prepare('SELECT data from sessions WHERE id=":id"');
            // specify bind parameter
            $stmt->bindParam(':id', $id);
            // execute query
            $stmt->execute();
            //fetch record
            $row = $stmt->fetch();
            //return data value
            return $row['data'];
        }//End of read function

        /*
         * defining write function
         * takes two arguments
         * $id as String
         * $data as text
         */
        public function write($id, $data)    {
            // prepare sql query
            $stmt = $this->db->prepare('REPLACE INTO sessions(id,data) VALUES (:id, :data)');
            // execute query
            $row_affected = $stmt->execute(array(':id'=>$id , ':data'=>$data));
            //return data value
            return $row_affected;
             
        }// End of write function
        
        
        /*
         * defining destroy funciton
         * takes one argument
         * $id as string
         */
        public function destroy($id)    {
            // prepare sql query
            $stmt = $this->db->prepare('DELETE FROM sessions WHERE id=:id');
            // execute query
            $row_affected = $stmt->execute(array(':id'=> $id));
            //return data value
            return $row_affected;     
        }// End of destroy function

        /*
         * defining gc function
         * takes one argument
         * $maxlifetime as timestamp
         */
        public function gc($maxlifetime)    {
            // prepare sql query
            $stmt = $this->db->prepare('DELETE FROM sessions WHERE DATE_ADD(last_accessed,INTERVAL :expire SECOND) < NOW()');
            // specify bind parameter
            $stmt->bindParam(':expire', $maxlifetime);
            // execute query
            $row_affected = $stmt->execute();
            //return data value
            return $row_affected;
            
        }// end of gc function

       
}// End of MySessionHandler


$handler = new DbSessionHandler();

session_set_save_handler(
    array($handler, 'open'),
    array($handler, 'close'),
    array($handler, 'read'),
    array($handler, 'write'),
    array($handler, 'destroy'),
    array($handler, 'gc')
    );

// the following prevents unexpected effects when using objects as save handlers
register_shutdown_function('session_write_close');
session_start();
//echo $savePath;
//print_r('okii');
//exit();
// proceed to set and retrieve values by key from $_SESSION

?>
