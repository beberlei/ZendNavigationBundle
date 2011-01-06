# Zend Navigation Bundle

This bundle integrates Zend Navigation into Symfony2 by providing view helpers and symfony taylored page types.

## Installation

Put it into your bundle list:

    new Bundle\ZendNavigationBundle(),

Zend Framework is already a dependency of Symfony so this is all rather simple to use.

## Configuration

Load the extension:

navigation.nav:
  containers:
    foo:
      root:
        route: root_route_name
        params:
          id: 1234
        pages:
          foo_page:
            route:  foo_page_route
            params:
               bar: baz
