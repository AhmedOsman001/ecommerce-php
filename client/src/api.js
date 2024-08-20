import axios from "axios";

export const getProducts = async () => {
  try {
    const response = await fetch(
      "http://localhost:3000/server/src/api/getProducts.php",
      {
        headers: {
          "Content-Type": "application/json",
        },
      }
    );

    if (!response.ok) {
      throw new Error("Network response was not ok");
    }

    const data = await response.json();
    return data;
  } catch (error) {
    console.error("Error fetching products:", error);
    throw error;
  }
};

// src/api/addProduct.js

export const addProduct = async (product) => {
  const response = await axios.post(
    "http://localhost:3000/server/src/api/addProduct.php",
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
    const response = await axios.post(
      "http://localhost:3000/server/src/api/deleteProducts.php",
      { skus: skus },
      {
        headers: {
          "Content-Type": "application/json",
        },
      }
    );
    console.log(response);
  } catch (error) {
    console.error("Error deleting products:", error);
    throw error;
  }
};
