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
    
    console.log(response.data);
    return response.data;
  } catch (error) {
    console.error("Error fetching products:", error);
    throw error;
  }
};

// src/api/addProduct.js

export const addProduct = async (product) => {
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
    console.log(response);
  } catch (error) {
    console.error("Error deleting products:", error);
    throw error;
  }
};
