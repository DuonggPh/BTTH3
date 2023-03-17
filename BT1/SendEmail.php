<?php 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';

interface EmailServerInterface {
	public function sendEmail($to, $subject, $message);
}

class EmailSender {
    private $emailServer;

    public function __construct(EmailServerInterface $emailServer) {
        $this->emailServer = $emailServer;
    }

    public function send($to, $subject, $message) {
        $this->emailServer->sendEmail($to, $subject, $message);
    }
}


class MyEmailServer implements EmailServerInterface {    


    private $host;
    private $port;
    private $username;
    private $password;
    
    public function __construct($host, $port, $username, $password){
        $this->host = $host;
        $this->port = $port;
        $this->username = $username;
        $this->password = $password;
    }


    public function sendEmail($to, $subject, $message) {
    // Implementation to send email using MyEmailServer 
        // $mailer = new PHPMailer\PHPMailer\PHPMailer();
        // $mailer->isSMTP();
        // $mailer->Host = $this->host;
        // $mailer->Port = $this->port;
        // $mailer->SMTPAuth = true;
        // $mailer->Username = $this->username;
        // $mailer->Password = $this->password;
        // $mailer->setFrom('noreply@example.com', 'Example');
        // $mailer->addAddress($to);
        // $mailer->Subject = $subject;
        // $mailer->Body = $body;

        // if (!$mailer->send()) {
        //     throw new Exception('Failed to send email: ' . $mailer->ErrorInfo);
        // }
        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host       = $this->host;                     //Set the SMTP server to send through
            $mail->Port       = $this->port;                                  //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->Username   = $this->username;
            $mail->Password   = $this->password;                            //SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
            $mail->CharSet ='UTF-8';

            //Recipients
            $mail->setFrom('detectiveduongz@gmail.com', 'Dương 21_Phan Thái');
            $mail->addAddress($to);     //Add a recipient

            //Content
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = $subject;
            $mail->Body    = $message;

            $mail->send();
            echo 'Message has been sent';
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }
}

// class Register {
//     private $db;
//     private $emailSender;
  
//     public function __construct($db, $emailSender) {
//       $this->db = $db;
//       $this->emailSender = $emailSender;
//     }
  
//     public function register() {
//       // Lấy dữ liệu từ form đăng ký
//       $name = $_POST['name'];
//       $email = $_POST['email'];
//       $password = $_POST['password'];
  
//       // Tạo mã kích hoạt tài khoản
//       $activation_code = md5($email . time());
  
//       // Lưu thông tin tài khoản vào database
//       $query = "INSERT INTO users (name, email, password, activation_code) VALUES ('$name', '$email', '$password', '$activation_code')";
//       $result = $this->db->query($query);
  
//       // Gửi email kích hoạt tài khoản
//       $subject = 'Activate Your Account';
//       $message = "Hi $name,<br><br>Thank you for registering with us. Please click on the following link to activate your account:<br><br>";
//       $message .= "<a href='http://example.com/activate.php?code=$activation_code'>Activate Now</a>";
//       $this->emailSender->sendEmail($email, $subject, $message);
  
//       // Hiển thị thông báo cho người dùng
//       echo "A confirmation email has been sent to your email address. Please click on the activation link to activate your account.";
//     }
// }
class RegisterController{

    private $db;
    private $emailSender;
  
    public function __construct($db, $emailSender) {
      $this->db = $db;
      $this->emailSender = $emailSender;
    }

    public function register() {
            $user   = $_POST['txtUser'];
            $mail   = $_POST['txtMail'];
            $pass1  = $_POST['txtPass1'];
            $pass2  = $_POST['txtPass2'];
            // Kiểm tra Mật khẩu có khớp ko
            $activation_code = md5(random_bytes(20));
            if($pass1 != $pass2){
                echo "<p style='color:red'>Mật khẩu không khớp</p>";
                // header("Location:register.php");
            }else{
                // Kiểm tra Tài khoản nó đã TỒN TẠI CHƯA
                try{
                    $conn = mysqli_connect('localhost','root','','btth3');
                }catch(Exception $e){
                    echo $e->getMessage();
                }
                $query = "SELECT * FROM users WHERE username = '$user' OR email='$mail'";
                $result = $this->db->query($query);
                if(mysqli_num_rows($result) > 0){
                    echo "<p style='color:red'>Tên đăng nhập hoặc Email đã được sử dụng</p>";
                }else{
                    // Lưu lại bản đăng kí vào CSDL
                    // $pass_hash = password_hash($pass1, PASSWORD_DEFAULT);
                    // $activation_code = md5(random_bytes(20));
                    // $insert_sql = "INSERT INTO users (username, email, password, activation_code) VALUES ('$user', '$mail', '$pass_hash', '$activation_code')";
                    // if(mysqli_query($conn,$insert_sql)){
                    //     echo "<p style='color:green'>Đăng kí thành công, vui lòng check Email để kích hoạt tài khoản</p>";
                    //     // Gửi Email chứa liên kết để kích hoạt
                    //     // Kích hoạt là gì?
                    $insert_query = "INSERT INTO users (username, email, password, activation_code) VALUES ('$user', '$mail', '$pass1', '$activation_code')";
                    $result_query = $this->db->query($insert_query);
                    $to = $mail;
                    $subject = 'Activate Your Account';
                    $message = "Hi $user,<br><br>Thank you for registering with us. Please click on the following link to activate your account:<br><br>";
                    $message .= "<a href='http://example.com/activate.php?code=$activation_code'>Activate Now</a>";
                    $this->emailSender->send($to, $subject, $message);

                }
    
                    
            }
    }
}


// $emailServer = new MyEmailServer('smtp.gmail.com', 465, 'detectiveduongz@gmail.com', 'upftwfkejymjidvp');
// $emailSender = new EmailSender($emailServer);
// $emailSender->send("detectiveduongz@gmail.com", "Test Email", "This is a test email.");
$db = new mysqli('localhost', 'root', '', 'btth3');
$emailServer = new MyEmailServer('smtp.gmail.com', 465, 'detectiveduongz@gmail.com', 'upftwfkejymjidvp');
$emailSender = new EmailSender($emailServer);
// Khởi tạo RegisterController
$registerController = new RegisterController($db, $emailSender);
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $registerController->register();
}

?>


<form action="sendEmail.php" method="post">
    <ul>
        <li>Username: <input type="text" name="txtUser" ></li>
        <li>Email: <input type="text" name="txtMail"></li>
        <li>Password: <input type="password" name="txtPass1"></li>
        <li>Confirm Password: <input type="password" name="txtPass2"></li>
        <button type="submit">Register</button>
    </ul>
</form>

