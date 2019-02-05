# SalesApi

Instructions: 
1. http://localhost/salesapi/order/dump.php : creates db table and populate with all orders
2. http://localhost/salesapi/order/create.php : adds any new orders to table
3. http://localhost/salesapi/order/read.php : returns mean average of all orders and a record of them
    - adding parameter 'email': return all orders for that customer and their mean value
    - adding parameter 'variant_id': return all orders including that variant of a product and their mean value
    
    
Examples: 
  http://localhost/salesapi/order/read.php?email=Tianna.Stiedemann@developer-tools.shopifyapps.com
  http://localhost/salesapi/order/read.php?variant_id=21485815627881
