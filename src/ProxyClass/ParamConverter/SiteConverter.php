<?php
// @codingStandardsIgnoreFile

/**
 * This file was generated via php core/scripts/generate-proxy-class.php 'Drupal\og_sm\ParamConverter\SiteConverter' "modules/og_sm/src".
 */

namespace Drupal\og_sm\ProxyClass\ParamConverter {

    /**
     * Provides a proxy class for \Drupal\og_sm\ParamConverter\SiteConverter.
     *
     * @see \Drupal\Component\ProxyBuilder
     */
    class SiteConverter implements \Drupal\Core\ParamConverter\ParamConverterInterface
    {

        use \Drupal\Core\DependencyInjection\DependencySerializationTrait;

        /**
         * The id of the original proxied service.
         *
         * @var string
         */
        protected $originalServiceId;

        /**
         * The real proxied service, after it was lazy loaded.
         *
         * @var \Drupal\og_sm\ParamConverter\SiteConverter|null
         */
        protected $service;

        /**
         * The service container.
         *
         * @var \Symfony\Component\DependencyInjection\ContainerInterface
         */
        protected $container;

        /**
         * Constructs a ProxyClass Drupal proxy object.
         *
         * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
         *   The container.
         * @param string $originalServiceId
         *   The service ID of the original service.
         *
         * @SuppressWarnings(PHPMD.LongVariable)
         */
        public function __construct(\Symfony\Component\DependencyInjection\ContainerInterface $container, $originalServiceId)
        {
            $this->container = $container;
            $this->originalServiceId = $originalServiceId;
        }

        /**
         * Lazy loads the real service from the container.
         *
         * @return object
         *   Returns the constructed real service.
         */
        protected function lazyLoadItself()
        {
            if (!isset($this->service)) {
                $this->service = $this->container->get($this->originalServiceId);
            }

            return $this->service;
        }

        /**
         * {@inheritdoc}
         */
        public function convert($value, $definition, $name, array $defaults)
        {
            return $this->lazyLoadItself()->convert($value, $definition, $name, $defaults);
        }

        /**
         * {@inheritdoc}
         */
        public function applies($definition, $name, \Symfony\Component\Routing\Route $route)
        {
            return $this->lazyLoadItself()->applies($definition, $name, $route);
        }

    }

}
