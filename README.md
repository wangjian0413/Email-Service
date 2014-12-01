Email-Service
=============

This is for Uber coding chanllenge.

Description
-------------
This email service accepts the necessary information and sends emails through 3rd party email providers. It includes two email service providers, mailgun (http://www.mailgun.com/) and mandrill (http://www.mandrill.com/). If one of the providers goes down, service can quickly failover to another. The service is deployed on AWS EC2 and can be access by  http://ec2-54-148-246-220.us-west-2.compute.amazonaws.com/.

This service is focusing on backend track.

Usage
------------
This service can help you send email via 3rd party email provider (sender's identity is not ensured). 
* Required fields: From, To, Text. 
* Optional fields: Subject, CC, BCC. 

If sending email is successful, the message will show which email provider sends your email. Ex: "Email has been sent correctly by Mail Gun". Otherwise, the message will show the failure reason. Ex: ""to" email is missing". The email fields To, CC, BCC suport multiple email addresses input (separated by comma), and the service will validate the email address format as well.

Technical implementation
------------
####Language and framework
The service is REST API web application (less than 1 year experience). It is mainly written in php for backend (2 years experience), and html/jquery for simple frontend (less than 1 year experience). It uses Zaphpa as HTTP router, which included in "include" folder. Zaphpa is a lightweight library which makes handling HTTP requests and implementing REST easier, and is good for PHP + REST development. More info about Zaphpa: http://zaphpa.org/. 

####Choose Email Sending Provider
There are multiple email providers support sending email. The service randomly generates a provider trying sequence via function shuffle() (in email_sent_handler.php) and then try to use each provider one by one in sequence order. If one provider goes down and failed sending email, it will try next until the email sending is successful or finish trying all providers. It can be smarter, see "Can be improved" session below. The service will check whether the input parameters are neccessary or not when calling email sending providers. 

####Can be improved if given more time
1. **Provider account issue**: Now the service is using testing account key for different email sending providers. Each key for each provider has a sending email limitation (EX: 10,000 emails per month). So our web app users permission should be controlled. It can be achieved by either adding a login system, or using a client certificate so that the service is not open to public. And also, the service should provide multiple account keys for each email provider in case one account uses up its sending limitation. 
2. **Some email sending functions not included**: Some email sending functions, like attachment, tag, delivery time, are not included. These functions are similar and easy to add, but require more testing. Didn't implement them due to limited time.
3. **Smart pick email provider**: The service doesn't consider about request load balance for different email provider, which can be result in a situation that one of the provides is used a lot but others are idle. Services load can be handled by implementation load balancer. 
4. **High availability**: Now only one instance serves the requests. Better to have HA for the service if having extra time.


Service deployment
------------
The service is deployed on AWS EC2 instance. You can access by DNS: http://ec2-54-148-246-220.us-west-2.compute.amazonaws.com/ or public IP: http://54.148.246.220/

About me
------------
LinkedIn: www.linkedin.com/pub/jian-wang/25/202/a01/


