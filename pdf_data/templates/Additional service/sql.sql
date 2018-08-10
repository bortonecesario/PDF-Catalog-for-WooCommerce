SELECT DISTINCT pd.description,
                pd.name AS name,
                p.image,
                p.model,
                p.product_id
                FROM %DB_PREFIX%product p 
                LEFT JOIN %DB_PREFIX%product_description pd ON (p.product_id = pd.product_id)
                LEFT JOIN %DB_PREFIX%manufacturer m ON (p.manufacturer_id = m.manufacturer_id) 
                WHERE p.product_id IN (%products_id%)
                AND pd.language_id = %language_id%