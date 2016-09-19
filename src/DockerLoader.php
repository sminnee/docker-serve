<?php

namespace SilverStripe\DockerServe;

class DockerLoader
{

    private $projectPath = null;

    /**
     * Create a new docker loader for the given project
     */
    function __construct($projectPath)
    {
        $this->projectPath = $projectPath;
    }

    /**
     * Return the docker image name to use
     */
    function getDockerImageName()
    {
        if(!file_exists("$this->projectPath/composer.json")) {
            throw new \LogicException("Can't find $this->projectPath/composer.json");
        }

        $json = json_decode(file_get_contents("$this->projectPath/composer.json"), true);
        if(!$json) {
            throw new \LogicException("Can't parse $this->projectPath/composer.json");
        }

        if(!isset($json['extra']['docker-container'])) {
            throw new \LogicException("$this->projectPath/composer.json doesn't contain extra.docker-container");
        }

        return $json['extra']['docker-container'];
    }

    /**
     * Pass execution through to the docker container
     */
    function passthru($command)
    {

        $workingPath = '/tmp/dockerserve';
        $pathMap = escapeshellarg($this->projectPath . ':' . $workingPath);
        $imageName = $this->getDockerImageName();

        $remoteCommand = "bash -c " . escapeshellarg("cd " . escapeshellarg($workingPath) . "; " . $command);
        $dockerCommand = "docker run -ti -v $pathMap $imageName $remoteCommand";

        echo "Calling $dockerCommand...\n";

        passthru($dockerCommand);
    }
}
