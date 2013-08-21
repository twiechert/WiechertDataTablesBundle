WiechertDataTablesBundle
========================

Extension for easily generating datatables from Doctrine entities.

# Core Features

- let the bundle generate DataTables for a given Doctrine entity
- supports associations
- use exclusion strategies to customize the ouput (which properties, max. graph depth)
- define actions that can be applied to an entity/row




# Example

###1. Annotate your entities:

There is an entity `Category` that points to the user who is its creator. We use the dafault Doctrine annotations and an annotation called `DisplayName` plus `Groups`.



    ```php
        <?php
          
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

			...
			}

    ```
	
The `DisplayName` annotation allows you to specify the column label and is optional.
By default the bundle will use the attributes name.
	
To customize which attributes and entities will be displayed you can use exclusion strategies (which will be explained later on) Such a strategy defines groups. 
	
###2. Create a configuration file:
	
	The bundle needs a configuration file that defines which entities can be displayed, how to find them.

  ``` Datatables:
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
      
              Product:
                  display_name:         "All the categories"
                  title:                "Category overview"
                  Actions:
                      PHPActions:
                         - {name: "Display", route: "wiechert_core_generic_entity_display"}
      
      
            NamedTables:
              Category:
                   get_subcategories:
                        title:               "sub categories"
                        description:         "get all sub categories"
                        select_table:         "Category"
                        select_table_bundle:  "WiechertDataTablesBundle"
                        joins:
                            - ["e0.rootcategory", "f"]
                        where_caluse:         "f.id = :id"
      
                   get_rootcategories:
                        title:               "root categories"
                        description:         "get all root categories"
                        select_table:         "Category"
                        select_table_bundle:  "WiechertDataTablesBundle"
                        joins:
                            - ["e0.subcategories", "f"]
                        where_caluse:         "f.id = :id"
      
                   get_products:
                        title:              "every product of this category"
                        description:        "list all producs of this category"
                        select_table:         "Product"
                        select_table_bundle:  "WiechertDataTablesBundle"
                        joins:
                            - ["e0.categories", "f"]
                        where_caluse:         "f.id = :id"
         
      
              Product:
                   get_categories:
                        title:               "Categories of the product"
                        description:         "get all categories"
                        select_table:         "Category"
                        select_table_bundle:  "WiechertDataTablesBundle"
                        joins:
                             - ["e0.products", "f"]
                        where_caluse:         "f.id = :id" ```


Most of it is self-explaining. Let me explain the array PHPActions. A PHPAction can be any kind of action (in a Symfony Controler), that is named. An action could be a deleteAction that deletes an entity.

An action can be applied to every entity within a DataTable. A list of all available actions per entity is rendered as a dropdown list in the DataTable:

When the bundle invokes that action, it passes the entity's name, its bundle name, its identifier and the name of the used exclusion strategy. You controller may not need all the information - therefore it's not mandatory to implement that interface.


