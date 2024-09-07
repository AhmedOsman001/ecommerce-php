import React, { useState, useRef } from 'react';
import { addProduct } from '../api';
import { useNavigate } from 'react-router-dom';

const AddProduct = () => {
    const [product, setProduct] = useState({ sku: '', name: '', price: '', type: '', size: '', weight: '', height: '', width: '', length: '' });
    const [error, setError] = useState(null);
    const navigate = useNavigate();
    const handleChange = (e) => {
        const { name, value } = e.target;
        setProduct(prevState => ({ ...prevState, [name]: value }));
    };

    const handleSubmit = async (e) => {
        e.preventDefault();
        try {
            await addProduct(product);
            setError(null);
            navigate('/')
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
                    <div className="input-group">
                        <label htmlFor="sku">SKU</label>
                        <input id="sku" name="sku" className='form__input' placeholder="SKU" value={product.sku} onChange={handleChange} required />
                    </div>
                    <div className="input-group">
                        <label htmlFor="Name">Name</label>
                        <input id="name" name="name" className='form__input' placeholder="Name" value={product.name} onChange={handleChange} required />
                    </div>
                    <div className="input-group">
                        <label htmlFor="Price">Price ($)</label>
                        <input type='number' id="price" name="price" className='form__input' placeholder="Price" value={product.price} onChange={handleChange} required />
                    </div>
                    <div className="input-group">
                        <label htmlFor="Type">Type Swicher</label>
                        <select id="productType" name="type" className='form__input' value={product.type} onChange={handleChange} required>
                            <option value="">Select Type</option>
                            <option value="DVD">DVD</option>
                            <option value="Book">Book</option>
                            <option value="Furniture">Furniture</option>
                        </select>
                    </div>

                    {product.type === 'DVD' &&

                        <>
                            <div className="input-group">
                                <label htmlFor="Size">Size</label>
                                <input type='number' id="size" name="size" className='form__input' placeholder="Size (MB)" value={product.size} onChange={handleChange} required />
                            </div>
                            <p className='helpful-information'>
                                Please provide size in MB.
                            </p>
                        </>
                    }
                    {product.type === 'Book' &&
                        <>
                            <div className="input-group">
                                <label htmlFor="Weight">Weight</label>
                                <input type='number' id="weight" name="weight" className='form__input' placeholder="Weight (Kg)" value={product.weight} onChange={handleChange} required />
                            </div>
                            <p className='helpful-information'>
                                Please provide weight in Kg.
                            </p>
                        </>
                    }
                    {product.type === 'Furniture' && (
                        <>
                            <div className="input-group">
                                <label htmlFor="Height">Height</label>
                                <input type='number' id="height" name="height" className='form__input' placeholder="Height (cm)" value={product.height} onChange={handleChange} required />
                            </div>
                            <div className="input-group">
                                <label htmlFor="Width">Width</label>
                                <input type='number' id="width" name="width" className='form__input' placeholder="Width (cm)" value={product.width} onChange={handleChange} required />
                            </div>
                            <div className="input-group">
                                <label htmlFor="Length">Length</label>
                                <input type='number' id="length" name="length" className='form__input' placeholder="Length (cm)" value={product.length} onChange={handleChange} required />
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
