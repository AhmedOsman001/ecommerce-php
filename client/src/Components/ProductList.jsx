import { useState, useEffect } from 'react';
import { getProducts, deleteProducts } from '../api';
const ProductList = () => {
    const [products, setProducts] = useState([]);
    const [selectedSkus, setSelectedSkus] = useState([]);

    useEffect(() => {
        const fetchProducts = async () => {
            const listproducts = await getProducts();
            setProducts(listproducts);
        };
        fetchProducts();
    }, []);

    const handleDelete = async () => {
        await deleteProducts(selectedSkus);
        const updatedProducts = await getProducts();
        setProducts(updatedProducts);
        setSelectedSkus([]);

    };

    const handleCheckboxChange = async (sku) => {
        setSelectedSkus(prevSelected =>
            prevSelected.includes(sku) ?
                prevSelected.filter(s => s !== sku) :
                [...prevSelected, sku]
        );
    };



    return (
        <div className='ProductList'>
            <header className='header'>
                <h1>Product List</h1>
                <div className="buttons">
                    <button onClick={() => window.location.href = '/add-product'}>ADD</button>
                    <button id='delete-product-btn' onClick={handleDelete}>MASS DELETE</button>
                </div>

            </header>
            <div className="line"></div>
            <ul>
                {products.map(product => (
                    <li key={product.sku} className='card'>
                        <p>{product.sku} </p>
                        <p>{product.name}</p>
                        <p>{product.price}$</p>
                        <p>{product.specificAttribute} </p>


                        <input
                            type="checkbox"
                            className="delete-checkbox"
                            value={product.sku}
                            onChange={() => handleCheckboxChange(product.sku)}
                        />
                    </li>
                ))}
            </ul>
        </div>
    );
};

export default ProductList;