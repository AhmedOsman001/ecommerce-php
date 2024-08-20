import { BrowserRouter as Router, Route, Routes } from 'react-router-dom'
import AddProduct from './Components/AddProduct'
import ProductList from './Components/ProductList'
function App() {
  
  return (
    <Router>
      <Routes>
        <Route path="/" element={<ProductList />} />
        <Route path="/add-product" element={<AddProduct />} />
      </Routes>
    </Router>
  )
}

export default App
