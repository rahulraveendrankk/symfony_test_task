<?php

namespace App\Command;

use App\Entity\Posts;
use App\Repository\PostsRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Console\Command\Command; 
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DownloadPost extends Command
{
    protected static $defaultName = 'app:download-post';

    public function __construct(ManagerRegistry $doctrine,PostsRepository $pr)
    {
        $this->pr=$pr;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName('app:download-post')
        ->setDescription('Download all post');  
        
    }
    protected function execute(InputInterface $input, OutputInterface $output)
    {   
        $url='https://jsonplaceholder.typicode.com/posts'; 
        $allPosts=$this->apiCall($url);

        $url='https://jsonplaceholder.typicode.com/users'; 
        $allUsers=$this->apiCall($url);

        $allUsers=json_decode($allUsers);
        $users=array();
        foreach ($allUsers as $key => $value) {
            $splitname=$this->splitName($value->name);
            $userid=(int)$value->id;
            $users[$userid]['name']= $splitname[0];
            $users[$userid]['surname']=$splitname[1];
        }
        
        $allPosts=json_decode($allPosts); 

        $result =  $this->pr->savePosts($allPosts,$users);
        if($result=='success')
            $output->write('All Posts saved into local DB.');
        else
            $output->write('Error occured, posts are not saved.');

        return Command::SUCCESS;
    }


    function apiCall($url)
    {
        $curl = curl_init();
        
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($curl, CURLOPT_USERPWD, "username:password");

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $result = curl_exec($curl);

        curl_close($curl);

        return $result;
    }

    function splitName($name) { 
        $name = trim($name);
        $parts = explode(" ", $name);
        $lastname = array_pop($parts);
        $firstname = implode(" ", $parts);  
        return array($firstname, $lastname);
    }

}