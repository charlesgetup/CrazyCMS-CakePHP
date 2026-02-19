CrazyCMS
=======

System Architecture
----------------

[CakePHP 2.4.5](http://www.cakephp.org) - The rapid development PHP framework

[ZeroMQ 4.0.3](http://zeromq.org/) - The message queue processor

[ZeroMQ PHP binding](https://github.com/mkoppanen/php-zmq.git) - ZeroMQ PHP interface

[Pcntl](http://php.net/pcntl) - Process Control support in PHP implements the Unix style of process creation, program execution, signal handling and process termination.

[POSIX](http://php.net/posix) - This module contains an interface to those functions defined in the IEEE 1003.1 (POSIX.1) standards document which are not accessible through other means.


GitHub Branches
------------

1, Master branch: store latest working codebase

2, Dev branch: fix bugs found in the current release, test bug fix result. This will generate minor releases.

3, Other branches: Those are feature branches. When start a new feature, like a plugin, create a new branch for it. Work on it under that branch, and after code review and testing, the new feature will be merged to dev to do a integration test and then finally, it will be merged to master branch and generate a major release.


DB Migrations
------------

1, Run the following commands to generate DB schema for top level models and plugin models. (Please note that schema generation in 2.x does not handle foreign key constraints.)

    ./Console/cake schema generate
    ./Console/cake schema generate --plugin EmailMarketing
    ./Console/cake schema generate --plugin LiveChat
    ./Console/cake schema generate --plugin Payment
    ./Console/cake schema generate --plugin TaskManagement
    ./Console/cake schema generate --plugin WebDevelopment
    
2, Generate incremented schema.php. 

    Run the above commands again. Will bring up the following choices:
    
    Generating Schema...
    Schema file exists.
     [O]verwrite
     [S]napshot
     [Q]uit
    Would you like to do? (o/s/q)
    
    Choosing [s] (snapshot) will create an incremented schema.php. So if you have schema.php, it will create schema_2.php and so on. 
    
3, Restore schema.

    Restore to any of these schema files at any time by running:
    
    ./Console/cake schema update -s 2
    
    Where 2 is the snapshot number you wish to run. The schema shell will prompt you to confirm you wish to perform the ALTER statements that represent the difference between the existing database the currently executing schema file.

    You can perform a dry run by adding a --dry to your command.
    
4, (Optional) Some Db table requires some default data or fixed data. If so, put those data in schema `after($event = array())` method.

    The $event param holds an array with two keys. One to tell if a table is being dropped or created and another for errors. Examples:

    array('drop' => 'posts', 'errors' => null)
    array('create' => 'posts', 'errors' => null)
    
    Adding data to a posts table for example would like this:
    
    App::uses('Post', 'Model');
    public function after($event = array()) {
        if (isset($event['create'])) {
            switch ($event['create']) {
                case 'posts':
                    App::uses('ClassRegistry', 'Utility');
                    $post = ClassRegistry::init('Post');
                    $post->create();
                    $post->save(
                        array('Post' =>
                            array('title' => 'CakePHP Schema Files')
                        )
                    );
                    break;
            }
        }
    }
    
    The before() and after() callbacks run each time a table is created or dropped on the current schema.
    
    When inserting data to more than one table you�ll need to flush the database cache after each table is created. Cache can be disable by setting $db->cacheSources = false in the before action().
    
    public $connection = 'default';
    
    public function before($event = array()) {
        $db = ConnectionManager::getDataSource($this->connection);
        $db->cacheSources = false;
        return true;
    }
    
    If you use models in your callbacks make sure to initialize them with the correct datasource, lest they fallback to their default datasources:
    
    public function before($event = array()) {
        $articles = ClassRegistry::init('Articles', array(
            'ds' => $this->connection
        ));
        // Do things with articles.
    }


What to do when re-/start the server
------------

1, Run the following command to start ZMQ server (& workers)

    ./Console/cake  zmq_multithreaded_server --max_worker_amount 5 --debug 1 --debug_output_method error_log
    
2, Run the following command to start ZMQ client

    ./Console/cake  zmq_multithreaded_client --max_parallel_threads 10 --job_fetch_interval 1 --poll_fetch_interval 1 --max_fetch_amount 10 --max_idel_time 300 --debug 1 --debug_output_method error_log

3, How to shutdown ZMQ server and client.

    The ZMQ client has a max idel time. When no job in the queue or no job can be done (server is down), the client starts to calculate the idel time. 
    If the idel time exceeds the max idel time, the client will send a "TERMINATE" message to the server and this will trigger the server shutdown procedure.
    After sending the server termination message, client will quit itself.

4, When we want to shutdown the ZMQ server manually, we need to run the following commands

	sudo pkill php
	
	or 
	g
	for i in `ps aux | grep {crazycms} | awk '{print $2}'`; do kill -9 $i; done
	for i in `cat /proc/mounts | grep {crazycms} | awk '{print $2}'`; do umount $i; done
	
	{crazycms} is the Linux server user
	
5, If ZMQ client keeps sending WAKEUP message to server, this means the ipc communication is not working. Delete all ipc files (`sudo rm /tmp/*.ipc`) and restart the apache server.

6, If cannot SMTP to Postfix, double check the port is 587 or not. And make sure the following services are running in mail server. `sudo systemctl status postfix / amavisd / dovecot / opendkim / spamassassin / amavisd / clamd@amavisd / clamav-freshclam / clamd@scan.service`

7, Mail server `mail.crazysoft.com.au` SSL certificate needs manually renew because it is using DNS challenge for now.

	
Using Composer (component) to install vendors
------------

1, `cd {absolute path}/app`. e.g. `cd /Users/yanfengli/Workspace/PHP/CrazyCMS/app`

2, Run `Console/cake composer.c install` or `Console/cake composer.c require opauth/opauth:0.*`

3, (Optional) If need to add composer option(s) to composer command, e.g. `composer update --no-dev`, use quote to wrap the command, like `Console/cake composer.c 'update --no-dev'`

4, After git commit, double check the vendor files in git. If file not there, we need to manually run the `composer install` in server

Unit test
------------
1, Run command to start unit test, e.g. ./app/Console/cake test app Controller/SystemEmailController --stderr


Obfuscate JS
------------
1, All if clause must contain {}.
2, When define var, code must ends with ";".

    e.g. 
    var a = function(){ console.log("This is wrong"); }
    var b = function(){ console.log("This is right"); }; 
    
3, When debug JS, stop AAEncoder first and then stop obfuscator if issue remians