import axios from "axios";

export const getProducts = async () => {
  try {
    const response = await axios.get(
      "https://ecommerce-php-production.up.railway.app/",
      {
        headers: {
          "Content-Type": "application/json",
        },
      }
    );
    
    return response.data;
  } catch (error) {
    console.error("Error fetching products:", error);
    throw error;
  }
};

// src/api/addProduct.js

export const addProduct = async (product) => {
  try {
    const response = await axios.post(
      "https://ecommerce-php-production.up.railway.app/",
      product,
      {
        headers: {
          "Content-Type": "application/json",
        },
      }
    );
    return response.data;
  } catch (error) {
    console.error("Error adding product:", error);
    throw error;
  }
};

export const deleteProducts = async (skus) => {
  try {
    const response = await axios.delete(
      "https://ecommerce-php-production.up.railway.app/",
      {
        headers: {
          "Content-Type": "application/json",
        },
        data: { skus: skus },
      }
    );
  } catch (error) {
    console.error("Error deleting products:", error);
    throw error;
  }
};
