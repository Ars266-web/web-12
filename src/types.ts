export interface Product {
  id: string;
  name: string;
  origin: string;
  category: 'single-origin' | 'blend' | 'espresso' | 'decaf';
  price: number;
  weight: string;
  roast: 'light' | 'medium' | 'dark';
  description: string;
  notes: string[];
  image: string;
  rating: number;
}

export interface CartItem {
  product: Product;
  quantity: number;
}
