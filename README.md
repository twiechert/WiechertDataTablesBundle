WiechertDataTablesBundle
========================

This Symfony 2 Bundle can generate a [DataTable](https://datatables.net/ "DataTables Plugin") (the jQuery Plugin) from a Doctrine entity and handles the
server-side progressing. The **WiechertDataTablesBundle** has further interesting features that are covered in the example section.

#####DataTable generated for a position (of an order and its referenced entities):
![](http://public.softwareentwicklung-wiechert.de/documentation/Datatables/position-separation-explination.png)

# Table of contents
- Core Features
- Installation
- Example
- Customize view
- Configuration Reference

# Core Features

- let the bundle generate DataTables for a given Doctrine entity and
- server-side searching, sorting, JSON serialization
- customizable views using exclusion strategies
- the EntityDisplayer creates a readable view for your entities and shows additional information using NamedDatatables



# Installation

###1. Download via composer:


###2. Allow routing:

You have to allow the bundle's routing configuration. Add the following code to your routing.yml

```
wiechert_data_tables:
	resource: "@WiechertDataTablesBundle/Controller/"
    type:     annotation
    prefix:   /
```

Feel free to customize the prefix.


###3. Configuration:

Please work trough the example section and create a configuration file applicable to your use case.

# Example

###1. Annotate your entity classes:

There could be an entity class `Category` that points to a class  `User` who is its creator. We use the default Doctrine annotations and additionally annotations called `DisplayName` and `Groups`.

#####Annotating the category class:
```php
<?php
  
namespace Wiechert\DataTablesBundle\Entity;

      use Doctrine\ORM\Mapping as ORM;
      use JMS\Serializer\Annotation as Serializer;
      use Wiechert\DataTablesBundle\Annotations\DisplayName;


      /**
       * @ORM\Entity
       * @ORM\Table(name="category")
       * @DisplayName(name="Category")
       */
      class Category
      {
      	 /**
      	  * @ORM\Id
      	  * @ORM\Column(type="integer", name="id")
      	  * @ORM\GeneratedValue(strategy="AUTO")
      	  * @Serializer\Groups({"Id"})
      	  * @DisplayName(name="Id")
      	  */
      	 protected $id;
      	 

         /**
      	* @ORM\Column(type="string", length=60)
      	* @Serializer\Groups({"Simple", "Name"})
      	* @DisplayName(name="Label")
      	*/
      	protected $label;



      	/**
      	 * @ORM\OneToMany(targetEntity="Category", mappedBy="rootcategory")
      	 * @Serializer\Groups({"ComplexeReference"})
      	**/
      	protected $subcategories;



      	/**
      	 * @ORM\ManyToOne(targetEntity="Wiechert\DataTablesBundle\Entity\User", inversedBy="categories")
      	 * @Serializer\Groups({"SimpleReference"})
      	 * @DisplayName(name="Creator")
      	 **/
      	protected $creator;

      	/**
      	 * @ORM\ManyToOne(targetEntity="Wiechert\DataTablesBundle\Entity\Category", inversedBy="subcategories")
      	 * @Serializer\Groups({"SimpleReference"})
      	 * @DisplayName(name="Root Category")
      	 **/
      	protected $rootcategory;

      	/**
      	 * @ORM\ManyToMany(targetEntity="Product", mappedBy="categories")
      	 * @Serializer\Groups({"ComplexeReference"})
      	 * @DisplayName(name="Products")
      	 */
      	protected $products;
      	
      	.....
      	}

```
	
#####Annotating the user class:
``` php
	
<?php

namespace Wiechert\DataTablesBundle\Entity;

	use Doctrine\ORM\Mapping as ORM;
	use JMS\Serializer\Annotation as Serializer;
	use Wiechert\DataTablesBundle\Annotations\DisplayName;


	/**
	 * @ORM\Entity
	 * @ORM\Table(name="user")
	 * @DisplayName(name="User")
	 */
	class User
	{
		 /**
		  * @ORM\Id
		  * @ORM\Column(type="integer", name="id")
		  * @ORM\GeneratedValue(strategy="AUTO")
		  * @Serializer\Groups({"Id"})
		  * @DisplayName(name="Id")
		  */
		 protected $id;
		 

         /**
          * @ORM\Column(type="string", length=60)
          * @Serializer\Groups({"Simple", "Name"})
          * @DisplayName(name="Username")
          */
         protected $username;

         /**
          * @ORM\OneToMany(targetEntity="Category", mappedBy="creator")
          * @Serializer\Groups({"ComplexeReference"})
          **/
         protected $categories;


         /**
          * @ORM\OneToMany(targetEntity="Order", mappedBy="user")
          * @Serializer\Groups({"ComplexeReference"})
          **/
         protected $orders;

		...
		}

```
	
The `DisplayName` annotation allows you to specify the label of the rendered column and is optional.
By default the bundle will use the attributes name.
	
To customize which properties and entities will be displayed you can use exclusion strategies (which will be explained more detailed later on). Our approach is to **assign properties to groups** using the `Groups` annotation.Then we use an exclusion strategie that will decide based on the properties group(s), whether to reflect it it or not.
	
###2. Create a configuration file:

The bundle needs a configuration file that defines which entities can be displayed, where to find them, which actions can be applied and so on (see Configuration Reference).
Create an empty configuration file and reference it in you app/config.yml:

```
imports:
   - { resource: "@Bundle/Resources/config/yourfile.yml" }
```
A configuration file for the given scenario could look like:
```
wiechert_data_tables:
    Datatables:
        Bundles:
          WiechertDataTablesBundle:
            namespace: "Wiechert\\DataTablesBundle\\Entity\\"
            Tables:
              Category:
                  display_name:         "All the categories"
                  title:                "Category overview"
                  Actions:
                    PHPActions:
                      - {name: "Display", route: "wiechert_core_generic_entity_display"}
                  NamedTables:
                      get_subcategories:
                          title:               "sub categories"
                          description:         "get all sub categories"
                          select_table:         "Category"
                          select_table_bundle:  "WiechertDataTablesBundle"
                          joins:
                              - {join: "e0.rootcategory", alias: "a"}
                          where_caluse:         "a.id = :id"

                      get_rootcategorie:
                            title:               "root categorie"
                            description:         "get  root categorie"
                            select_table:         "Category"
                            select_table_bundle:  "WiechertDataTablesBundle"
                            joins:
                                - {join: "e0.subcategories", alias: "a"}
                            where_caluse:         "a.id = :id"

                      get_products:
                           title:              "every product of this category"
                           description:        "list all producs of this category"
                           select_table:         "Product"
                           select_table_bundle:  "WiechertDataTablesBundle"
                           joins:
                              - {join: "e0.categories", alias: "a"}
                           where_caluse:         "a.id = :id"

              Product:
                  display_name:         "All the products"
                  title:                "Product overview"
                  Actions:
                      PHPActions:
                         - {name: "Display", route: "wiechert_core_generic_entity_display"}
                  NamedTables:
                     get_categories:
                                      title:               "Categories of the product"
                                      description:         "get all categories"
                                      select_table:         "Category"
                                      select_table_bundle:  "WiechertDataTablesBundle"
                                      joins:
                                           - {"e0.products", alias: "a"}
                                      where_caluse:         "a.id = :id"

                     get_buyers:
                           title:               "Buyery of this product"
                           description:         "get all buyers"
                           select_table:         "User"
                           select_table_bundle:  "WiechertDataTablesBundle"
                           joins:
                                - {join: "e0.orders", alias: "a"}
                                - {join: "a.positions", alias: "b"}
                                - {join: "b.product", alias: "c"}
                           where_caluse:         "c.id = :id"

              User:
                  display_name:         "All the users"
                  title:                "User overview"
                  Actions:
                      PHPActions:
                         - {name: "Display", route: "wiechert_core_generic_entity_display"}


```


####2.1 Define PHP-Actions

Most of it is self-explaining. Let me explain the array PHPActions. A PHPAction can be any kind of action (in a Symfony Controler), that is named. An action could be a `deleteEntityByIdAction` that deletes an entity.
An action applicable for a Category could be `createSubcatetegoryAction` that renders in form allowing you to
add a sub-category.

An action can be applied to every entity within a DataTable. A list of all available actions per entity is rendered as a dropdown list in the DataTable:

![](http://public.softwareentwicklung-wiechert.de/documentation/Datatables/category-search.png)

When the bundle invokes that action, it passes the entity's name, its bundle name, its identifier and the name of the used exclusion strategy. You controller may not need all the information - therefore it's not mandatory to implement that interface.

#####Example: a delete action:

```php
 ...
    public function deleteEntityAction($bundle, $entity, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $query = $em->createQueryBuilder()
            ->select("e")
            ->from($bundle.$entity, "e")
            ->where("e.id = :id")
            ->getQuery()
            ->setParameter('id', $id);

        try {
            $object = $query->getSingleResult();
            $em->remove($object);
            $em->flush();
            // render success page

        } catch(\Exception $e) {
           // render error page

        }


    }
    ..
```

####2.2 Define NamedDatatables

An interesting feature are  NamedDatatables. These DataTables display additional information on a  certain entity.
The given configuration allows the bundle to display all subcategories of a given category or all related products (`get_subcategories`).

#####Other possible scenarios:
- display all positions of an order
- display orders that contain a certain product
- display all users of a group ...

Simply configure a chain of inner joins, starting from the entity you actually want to display(!).

The NamedDatatables are listed when using the **EntityDisplayer**. The EntityDisplayer generates a readable view for a given entity. This view shows the entity's properties and all related NamedDatatables:

#####EntityDisplayer for a Product:
![](http://public.softwareentwicklung-wiechert.de/documentation/EntityDisplayer/product-example.png)

#####EntityDisplayer for a Category:
![](http://public.softwareentwicklung-wiechert.de/documentation/EntityDisplayer/category-example.png)

###3. Display your DataTables:

The route is: `http://local.test/app_dev.php/~/datatable/generate/{bundle}/{entity}/{strategy}`

- {bundle}, {entity}: as configured
- {strategy}: name of exclusion strategy (predefined: Simple or Extended, or your own implementation)

(whereas ~ has be replaced by your defined suffix)

**Example:** `http://local.test/app_dev.php/datatable/generate/WiechertDataTablesBundle/Category/Extended`

The EntityDisplayer can either be applied as an action or alternatively call it using the route:

`http://local.test/app_dev.php/~/datatable/display/{bundle}/{entity}/{strategy}/{id}`

**Example:** `http://local.test/app_dev.php/datatable/display/WiechertDataTablesBundle/Product/Simple/2`

- id: the entity's identifier (has to be named id)

# Customize view

###1. Usage of exclusion strategies

Exclusion strategies affect the reflection process whilst deciding to skip a property (or class) or not.

####1.1 Group-based exclusion strategies

A popular approach is to define groups. So if a a property (or furthermore a member) does not belong to at least one of predefined groups, it is skipped.

The abstract class `TreeGroupExclusionStrategy` expects subclasses to implement the methods `getGroups` and `getMaxDepth`.

 - `getGroups`: has to return a multidimensional array of group names, whereas the first dimension matches the graph depth.
    The second dimension matches the groups allowed at that graph depth.


The reference implementation `ExtendedStrategy` allows a max. graph depth of 3.

```php
namespace Wiechert\DataTablesBundle\TableGenerator\EntityReflection\Strategies;


class ExtendedStrategy extends TreeGroupExclusionStrategy
{
    /**
     * @return string[]
     */
    public  function getGroups()
    {
        return array ( array(parent::$idGruppe, parent::$simpleGruppe, parent::$simpleReferenceGruppe),
                      array(parent::$idGruppe, parent::$simpleGruppe,  parent::$simpleReferenceGruppe), array(parent::$idGruppe, parent::$simpleGruppe));
    }


    /**
     *
     * @return int
     */
    public function getMaxDepth()
    {
        return 3;
    }

    /**
     * Returns the name of the display strategy.
     *
     * @return string
     */
    public function getName()
    {
        return "ExtendedStrategy";
    }


}
```
####1.2 Other exclusion strategies

You can implement the IExclusionStrategy `interface Wiechert\DataTablesBundle\TableGenerator\EntityReflection\Strategies\IExclusionStrategy`.


# Configuration Reference
