<?php

declare(strict_types=1);

namespace App\Core\App;

use App\Interfaces\DependencyContainerInterface;

class Core
{
    public function handleRequest(
        array $handler,
        DependencyContainerInterface $c
    ) {
        $controllerName = $handler['controller'];
        $method = $handler['method'];
        $params = $handler['params'];

        $methodArgs = [];
        $refMethod = new \ReflectionMethod($controllerName, $method);

        foreach ($refMethod->getParameters() as $param) {
            $type = $param->getType();

            // check if type is Builtin, means type IS A class (otherwise it's not needed to be initialized)
            if ($type && !$type->isBuiltin()) {
                $className = $type->getName();

                // Return message if class not initiated in Dependency Container as expected
                if (null === $c->get($className)) {
                    return [
                        'status' => 'error',
                        'payload' => "Class {$className} does not exist. Initiate in DependencyContainer on launch."
                    ];
                }

                $methodArgs[] = $c->get($className);
            } elseif (!empty($params)) {
                $methodArgs[] = array_shift($params);
            }
        }

        // running controller and method, the result will be transmitted to json response
        return $refMethod->invokeArgs(new $controllerName(), $methodArgs);
    }

}
