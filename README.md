WiechertDataTablesBundle
========================

Extension for easily generating DataTables from Doctrine entities.

# Core Features

- let the bundle generate DataTables for a given Doctrine entity
- use exclusion strategies to customize the output
- define actions that can be applied to an entity/row

TODO
- describe installation
- configuration reference

# Example

###1. Annotate your entities:

There could be an entity `Category` that points to the user who is its creator. We use the default Doctrine annotations and an annotation called `DisplayName` plus `Groups`.

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
      	 * @ORM\ManyToMany(targetEntity="Product", inversedBy="categories")
      	 * @Serializer\Groups({"ComplexeReference"})
      	 * @DisplayName(name="Products")
      	 */
      	protected $products;
      	
      	.....
      	}

```
	
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
	
The `DisplayName` annotation allows you to specify the column label and is optional.
By default the bundle will use the attributes name.
	
To customize which attributes and entities will be displayed you can use exclusion strategies (which will be explained later on) Such a strategy defines groups. 
	
###2. Create a configuration file:

The bundle needs a configuration file that defines which entities can be displayed, where to find them, which actions can be applied and so on (see Configuration Reference).
Reference the configuration file in you app/config.yml

```
imports:
   - { resource: "@Bundle/Resources/config/yourfile.yml" }
```
The configuration file for the scenario could look like:
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

Most of it is self-explaining. Let me explain the array PHPActions. A PHPAction can be any kind of action (in a Symfony Controler), that is named. An action could be a deleteAction that deletes an entity.

An action can be applied to every entity within a DataTable. A list of all available actions per entity is rendered as a dropdown list in the DataTable:

When the bundle invokes that action, it passes the entity's name, its bundle name, its identifier and the name of the used exclusion strategy. You controller may not need all the information - therefore it's not mandatory to implement that interface.
An example:

```php
 ...
    public function genericDeleteAction($bundle, $entity, $id)
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

####2.2 Define named Datatables

A good feature are named Datatables. These Datatables display additional information on a  certain entity.
The given configuration allows the bundle to display all subcategories of a given category or all related products.

#####Other scenarios:
- display all positions of an order
- display orders that contain a certain product
- display all users of a group ...

Simply configure a chain of Left-Joins, starting from the entity you actually want to display(!).


###3. Display your DataTables:

The route is: `http://local.test/app_dev.php/~/datatable/generate/{bundle}/{entity}/{strategy}`

- bundle, entity: as configured
- strategy: name of exclusion strategy (predefined: Simple or Extended)

(whereas ~ has be replaced by your defined suffix)

Example: `http://local.test/app_dev.php/datatable/generate/WiechertDataTablesBundle/Category/Extended`

# Customize output

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
