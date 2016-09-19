<?php

namespace SilverStripe\DockerServe;

class DockerLoader
{

    private $projectPath = null;
    private $options = null;

    /**
     * Create a new docker loader for the given project
     */
    function __construct($projectPath, $options = [])
    {
        $this->projectPath = $projectPath;
        $this->options = $options;
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
     * Return a new DockerLoader that provides a port mapping
     */
    function withPortMapping($remote, $local)
    {
        $options = $this->options;
        if (!isset($options['portMappings'])) {
            $options['portMappings'] = [];
        }
        $options['portMappings'][] = "$local:$remote";
        return new DockerLoader($this->projectPath, $options);
    }

    /**
     * Pass execution through to the docker container
     */
    function passthru($command)
    {

        $workingPath = '/tmp/dockerserve';
        $pathMap = escapeshellarg($this->projectPath . ':' . $workingPath);
        $imageName = $this->getDockerImageName();

        $portMappings = "";
        if ($this->options['portMappings']) {
            foreach ($this->options['portMappings'] as $portMapping) {
                $portMappings .= " -p " . escapeshellarg($portMapping);
            }
        }

        $remoteCommand = "bash -c " . escapeshellarg("cd " . escapeshellarg($workingPath) . "; " . $command);
        $dockerCommand = "docker run -ti -P$portMappings -v $pathMap $imageName $remoteCommand";

        echo "Calling $dockerCommand...\n";

        passthru($dockerCommand);
    }
}
