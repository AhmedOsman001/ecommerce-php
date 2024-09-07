import axios from "axios";

export const getProducts = async () => {
  try {
    const response = await axios.get(
      "http://localhost:3000/server/index.php",
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

export const addProduct = async (product) => {
  try {
    const response = await axios.post(
      "http://localhost:3000/server/index.php",
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
    await axios.delete("http://localhost:3000/server/index.php", {
      headers: {
        "Content-Type": "application/json",
      },
      data: { skus: skus },
    });
  } catch (error) {
    console.error("Error deleting products:", error);
    return error;
  }
};
