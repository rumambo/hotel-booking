## vvStore
Script online storefronts. The simplest, fastest possible. Nothing extra.
The engines do not require databases, as they use the Sqlite3 file database.


<p>
Script online storefronts. The simplest, fastest possible. Nothing extra.
The engines do not require databases, as they use the Sqlite3 file database.
</p>

<ul>
<li>FullAjax page load without refresh</li>
<li>Responsive</li>
<li>Easy Installation</li>
<li>Clean code</li>
<li>Cross Browser Support</li>
<li>Simple but Powerful admin panel</li>
<li>Dashboard statistics</li>
<li>Thumbnail & Multiple Images for Product</li>
<li>Multi-level Unlimited Categories</li>
<li>Unlimited Product adding System</li>
<li>Custom meta tag adding system for better SEO results</li>
<li>Static pages</li>
<li>HTMl5 Markup</li>
<li>Microformats google</li>
<li>Ready to use</li>
<li>Realtime cart and checkout calculations</li>
<li>Email notifications for order</li>
<li>Instant search</li>
<li>Does not use dependencies like laravel, symfony and other framework</li>
<li>Template on clean html with inserts like {content}, {title}</li>
<li>Don't need MySQL, database on SQLite3</li>
<li>and much more…</li>
</ul>

<p>
The script is designed in such a way that it would be only the most necessary functionality, the necessary minimum for ordering.
</p>

## Installation
Copy the script to the root and set the rights to write:
* /uploads/images
* /uploads/products
* /uploads/products/md/
* /uploads/products/sm/
* /core/data/db.sqlite

Write in robots.txt which lies at the root of your domain - Sitemap and Host

If necessary, set up redirects (www or without) in .htaccess uncommenting the lines:
* RewriteCond %{HTTP_HOST} ^www.site.ru$ [NC]
* RewriteRule ^(.*)$ http://site.ru/$1 [R=301,L]


## Using
Access to the admin panel **yoursite.com/cp**	Password **admin**
The site map is generated automatically and is available at **yoursite.com/sitemap.xml**


## Template Description
**main.tpl** - the main page in it gathers everything and displays
* {headers} - output of meta tags and page title
* {total_qty} - number of items in the cart
* {total_cost} - price of goods in the basket
* {val} - currency prefix
* {address} - address from settings
* {content} - page content output
* {domen} - site domain
* {Y} - this year

**404.tpl** - 404 page template, no embedded tags

**nav.tpl** - pagination pattern
* [prev-link][/prev-link] - first link block
* {pages} - page list
* [next-link][/next-link] - last link block

**search.tpl** - search results page template
* {breadcrumb} - bread crumb output
* {title} - search page title
* {content} - search page content
* {pagination} - pagination search page

**search.ajax.tpl** - dropdown search pattern
* {title} - drop-down search page title
* {content} - content of the dropdown page

**static.tpl** - static page display template
* {home_title} - the output of the default title for breadcrumbs
* {short_title} - output short names for breadcrumbs
* {title} - static page name
* {static} - static page body

**start.tpl** - start page template
* {random_products} - output of random goods
* {viewed_products} - the goods that have already been viewed
* {desc} - text on the main

**random_products.tpl** - product display template on the main
* {full_link} - slug product links
* {img} - path to image pictures
* {title} - product name
* {product-id} - product id
* {cost} - cost
* {val} - currency

**cart.popup.tpl** - popup cart pattern
* [empty][/empty] - show block if cart is empty
* [not_empty][/not_empty] - show block if cart is not empty
* {cart} - list of products in cart
* {total_cost} - total cost
* {val} - currency

**checkout.tpl** - checkout page template
* [empty][/empty] - show block if cart is empty
* [not_empty][/not_empty] - show block if cart is not empty
* {delivery_method} - delivery methods
* {payment_method} - payment methods
* {cart} - list of products in cart
* {total_cost} - total cost
* {val} - currency

**checkout.cart.tpl** - cart template for checkout page
* {product_id} - product id
* {product_alt} - product slug
* {product_img} - path to image pictures
* {product_name} - product name
* {cost} - cost
* {val} - currency
* {qty} - quantity
* {price} - sum price

**checkout.success.tpl** - output page thanks for order after order

**product.catalog.tpl** - template output pages with product categories
* {breadcrumb} - breadcrumbs block
* {category_menu} - category block
* {title} - category name
* {goods} - list of products
* {pagination} - pagination
* {desc} - description

**product.full.tpl** - full product card template
* {breadcrumb} - breadcrumbs block
* {img1} - picture 1
* {img2} - picture 2
* {img3} - picture 3
* {img_js} - json array for lightGallery 
* {title} - product name
* {link-category} - category
* {fulldesc} - description
* {cost} - cost
* {val} - currency
* {product-id} - product id

**product.short.list.tpl** - short template for products drop out of search
* {full_link} - product slug
* {img} - product image
* {title} - product name
* {product-id} - product id
* {cost} - cost
* {val} - currency

**product.short.tile.tpl** - small item card template
* {full_link} - product slug
* {img} - product image
* {title} - product name
* {product-id} - product id
* {cost} - cost
* {val} - currency

**mails/mail.tpl** - letter template
* {domen} - site domain
* {mail_content} - letter content
* {email} - site e-mail

**mails/order_table.tpl** - order table template
* {list} - list of products
* {total_cost} - total cost
* {val} - currency 
* {noty} - if there is an order comment
 
**mails/txt_order_success_client.tpl** - text of the order letter to the buyer
* {domen} - site domain
* {fio} - name of the buyer
* {tel} - buyer's phone
* [tel][/tel] - show this block if there is a phone
* {city} - city
* [city][/city] - show this block if there is a city
* {email} - customer mail
* [email][/email] - show this block if there is mail
* {delivery} - delivery method
* [delivery][/delivery] - show this block if delivery method is specified
* {otd} - department
* [otd][/otd] - пrender this block if a department is indicated
* {payment} - payment method
* [payment][/payment] - show this block if payment method is specified
* {num_zakaz} - order id
* {order_table} - ordered basket block

**mails/txt_order_success_manager.tpl** - text of the order letter to manager
* {domen} - site domain
* {fio} - name of the buyer
* {tel} - buyer's phone
* [tel][/tel] - show this block if there is a phone
* {city} - city
* [city][/city] - show this block if there is a city
* {email} - customer mail
* [email][/email] - show this block if there is mail
* {delivery} - delivery method
* [delivery][/delivery] - show this block if delivery method is specified
* {otd} - department
* [otd][/otd] - show this block if department is indicated
* {payment} - payment method
* [payment][/payment] - show this block if payment method is specified
* {num_zakaz} - order id
* {order_table} - block cart

## For place script in subdirectory
To do this in the root .htaccess file change
```
RewriteBase / 
```
to
```
RewriteBase /subdir/
```
And also in the file start.php 
```
define ( 'AL', 'cp' ); // admin link
define ( 'FL', '' ); // frontend link
```
to 
```
define ( 'AL', 'subdir/cp' ); // admin link
define ( 'FL', '/subdir' ); // frontend link
```
Edit the template links where necessary.

## For any questions
Write to e-mail dev@xfor.top
