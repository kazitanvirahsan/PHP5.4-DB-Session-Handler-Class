<?php
    class FileSessionHandler implements SessionHandlerInterface  {
    
        private $savePath;
        
        /* 
         * defining method open
         * takes two arguments
         * $savePath
         * $sessionName
         */
        public function open($savePath, $sessionName)  {
            //print($savePath);
            //exit();
            $this->savePath = $savePath;
            
            //$this->savePath = "c:\\session";
            
            if (!is_dir($this->savePath)) {
                mkdir($this->savePath, 0777);
            }

            return true;
        }// End of open function

    
        /*
         * defining method close
         * takes no arguments
         */
        public function close()    {
            return true;
        }// End of close function

        
        /*
         * defining method read
         * Takes one arfument
         * $id string
         */
        public function read($id)    {
            return (string)@file_get_contents("$this->savePath/sess_$id");
        }//End of read function

        /*
         * defining write function
         * takes two arguments
         * $id as String
         * $data as text
         */
        public function write($id, $data)    {
            //print($id);
            //print($data);
            //exit();
            return file_put_contents("$this->savePath/sess_$id", $data) === false ? false : true;
        }// End of write function
        
        
        /*
         * defining destroy funciton
         * takes one argument
         * $id as string
         */
        public function destroy($id)    {
            $file = "$this->savePath/sess_$id";
        
            if (file_exists($file)) {
                unlink($file);
            }

            return true;
        }// End of destroy function

        /*
         * defining gc function
         * takes one argument
         * $maxlifetime as timestamp
         */
        public function gc($maxlifetime)    {
            foreach (glob("$this->savePath/sess_*") as $file) {
                if (filemtime($file) + $maxlifetime < time() && file_exists($file)) {
                    unlink($file);
            }
        }// end of gc function

        return true;
    }
}// End of MySessionHandler


$handler = new FileSessionHandler();

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