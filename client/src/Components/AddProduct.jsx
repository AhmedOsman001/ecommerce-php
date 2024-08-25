import React, { useState, useRef } from 'react';
import { addProduct } from '../api';

const AddProduct = () => {
    const [product, setProduct] = useState({ sku: '', name: '', price: '', type: '', size: '', weight: '', height: '', width: '', length: '' });
    const [error, setError] = useState(null);
    const handleChange = (e) => {
        const { name, value } = e.target;
        setProduct(prevState => ({ ...prevState, [name]: value }));
    };

    const handleSubmit = async (e) => {
        e.preventDefault();
        try {
            await addProduct(product);
            setError(null);
            window.location.href = '/';
            setProduct({ sku: '', name: '', price: '', type: '', size: '', weight: '', height: '', width: '', length: '' });
        } catch (error) {
            setError(error.message);
        }
        
    };

    

    return (
        <div className='add-product'>
            <header className='header'>
                <h1>Product Add</h1>
            </header>
            <div className="line"></div>
            {error && <div className="error">{error}</div>}
            <form id="product_form" onSubmit={handleSubmit}>
                <div className="form-input">
                    <div className="input">
                        <label htmlFor="sku">SKU</label>
                        <input id="sku" name="sku" placeholder="SKU" value={product.sku} onChange={handleChange} required />
                    </div>
                    <div className="input">
                        <label htmlFor="Name">Name</label>
                        <input id="name" name="name" placeholder="Name" value={product.name} onChange={handleChange} required />
                    </div>
                    <div className="input">
                        <label htmlFor="Price">Price ($)</label>
                        <input type='number' id="price" name="price" placeholder="Price" value={product.price} onChange={handleChange} required />
                    </div>
                    <div className="input">
                        <label htmlFor="Type">Type Swicher</label>
                        <select id="productType" name="type" value={product.type} onChange={handleChange} required>
                            <option value="">Select Type</option>
                            <option value="DVD">DVD</option>
                            <option value="Book">Book</option>
                            <option value="Furniture">Furniture</option>
                        </select>
                    </div>


                    {product.type === 'DVD' &&

                        <>
                            <div className="input">
                                <label htmlFor="Size">Size</label>
                                <input type='number' id="size" name="size" placeholder="Size (MB)" value={product.size} onChange={handleChange} required />
                            </div>
                            <p className='helpful-information'>
                                Please provide size in MB.
                            </p>
                        </>
                    }
                    {product.type === 'Book' &&
                        <>
                            <div className="input">
                                <label htmlFor="Weight">Weight</label>
                                <input type='number' id="weight" name="weight" placeholder="Weight (Kg)" value={product.weight} onChange={handleChange} required />
                            </div>
                            <p className='helpful-information'>
                                Please provide weight in Kg.
                            </p>
                        </>
                    }
                    {product.type === 'Furniture' && (
                        <>
                            <div className="input">
                                <label htmlFor="Height">Height</label>
                                <input type='number' id="height" name="height" placeholder="Height (cm)" value={product.height} onChange={handleChange} required />
                            </div>
                            <div className="input">
                                <label htmlFor="Width">Width</label>
                                <input type='number' id="width" name="width" placeholder="Width (cm)" value={product.width} onChange={handleChange} required />
                            </div>
                            <div className="input">
                                <label htmlFor="Length">Length</label>
                                <input type='number' id="length" name="length" placeholder="Length (cm)" value={product.length} onChange={handleChange} required />
                            </div>
                            <p className='helpful-information'>
                                Please provide dimensions in HxWxL format.
                            </p>
                        </>
                    )}
                </div>

                <div className="form-buttons">
                    <button type="submit">Save</button>
                    <button type="button" onClick={() => window.location.href = '/'}>Cancel</button>
                </div>

            </form>
        </div>

    );
};

export default AddProduct;
