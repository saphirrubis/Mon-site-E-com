<?php
namespace App\Service;

use Symfony\Component\Mime\Address;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;

// service qui permet de génére un mail
class MailerService {
                  public function __construct( private readonly MailerInterface $mailer){}
                    /** 
                     * @throws TransportExceptionInterface 
                     */
                  public function send (string $to, string $subject, string $templatetwing, array $context):void {
                               $email = (new TemplatedEmail())
                               ->from (new Address('noreply@monsiteecom.fr', 'MonsiteEcom')) 
                               ->to($to)
                               ->subject($subject)
                               ->htmlTemplate("mails/$templatetwing")
                               ->context($context);  

                               try{
                                    $this->mailer->send($email);
                               } catch(TransportExceptionInterface $transportException)
                               {
                                    // @var TransportExceptionInterface $transportException
                                    throw $transportException;
                               }
                              }
}