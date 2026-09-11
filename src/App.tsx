import { useState, useMemo } from 'react';
import { CartProvider, useCart } from './context/CartContext';
import Header from './components/Header';
import CategoryFilter from './components/CategoryFilter';
import ProductCard from './components/ProductCard';
import ProductDetail from './components/ProductDetail';
import Cart from './components/Cart';
import Checkout from './components/Checkout';
import { products } from './data/products';
import { Product } from './types';
import { Coffee, Leaf } from 'lucide-react';

function AppContent() {
  const [searchQuery, setSearchQuery] = useState('');
  const [selectedCategory, setSelectedCategory] = useState('all');
  const [selectedProduct, setSelectedProduct] = useState<Product | null>(null);
  const [showCheckout, setShowCheckout] = useState(false);
  const { isCartOpen, setIsCartOpen } = useCart();

  const filteredProducts = useMemo(() => {
    return products.filter((product) => {
      const matchesSearch =
        searchQuery === '' ||
        product.name.toLowerCase().includes(searchQuery.toLowerCase()) ||
        product.origin.toLowerCase().includes(searchQuery.toLowerCase()) ||
        product.notes.some((note) =>
          note.toLowerCase().includes(searchQuery.toLowerCase())
        );

      const matchesCategory =
        selectedCategory === 'all' || product.category === selectedCategory;

      return matchesSearch && matchesCategory;
    });
  }, [searchQuery, selectedCategory]);

  return (
    <div className="min-h-screen bg-stone-950 text-stone-100">
      <Header
        searchQuery={searchQuery}
        onSearchChange={setSearchQuery}
        onCartClick={() => setIsCartOpen(true)}
      />

      {/* Hero Section */}
      <section className="relative overflow-hidden">
        <div className="absolute inset-0 bg-gradient-to-br from-amber-950/40 via-stone-950 to-stone-950" />
        <div className="absolute top-0 right-0 w-96 h-96 bg-amber-900/10 rounded-full blur-3xl" />
        <div className="absolute bottom-0 left-0 w-64 h-64 bg-orange-900/10 rounded-full blur-3xl" />
        
        <div className="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-20">
          <div className="max-w-2xl">
            <div className="flex items-center gap-2 mb-4">
              <Leaf className="w-4 h-4 text-amber-500" />
              <span className="text-amber-400 text-sm font-medium tracking-wide uppercase">
                Ethically Sourced & Freshly Roasted
              </span>
            </div>
            <h2 className="text-3xl sm:text-5xl font-serif font-bold text-amber-50 leading-tight mb-4">
              Exceptional Coffee,
              <br />
              <span className="text-amber-400">Crafted with Care</span>
            </h2>
            <p className="text-stone-400 text-base sm:text-lg leading-relaxed max-w-xl">
              Discover our curated selection of specialty coffees from the world's 
              finest growing regions. Each bean is carefully sourced, roasted, and 
              delivered at peak freshness.
            </p>
          </div>
        </div>
      </section>

      {/* Products Section */}
      <section className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
        {/* Filters */}
        <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
          <div>
            <h3 className="text-xl sm:text-2xl font-serif font-bold text-amber-50">
              Our Collection
            </h3>
            <p className="text-stone-500 text-sm mt-1">
              {filteredProducts.length} coffee{filteredProducts.length !== 1 ? 's' : ''} available
            </p>
          </div>
          <CategoryFilter
            selectedCategory={selectedCategory}
            onCategoryChange={setSelectedCategory}
          />
        </div>

        {/* Product Grid */}
        {filteredProducts.length > 0 ? (
          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 sm:gap-6">
            {filteredProducts.map((product) => (
              <ProductCard
                key={product.id}
                product={product}
                onViewDetails={setSelectedProduct}
              />
            ))}
          </div>
        ) : (
          <div className="text-center py-16">
            <Coffee className="w-16 h-16 text-stone-700 mx-auto mb-4" />
            <h4 className="text-xl font-serif text-stone-400 mb-2">
              No coffees found
            </h4>
            <p className="text-stone-500">
              Try adjusting your search or filter criteria
            </p>
          </div>
        )}
      </section>

      {/* Footer */}
      <footer className="border-t border-stone-800 mt-12">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
          <div className="flex flex-col sm:flex-row items-center justify-between gap-4">
            <div className="flex items-center gap-2">
              <Coffee className="w-5 h-5 text-amber-600" />
              <span className="text-stone-400 font-serif">Ember & Bloom</span>
            </div>
            <p className="text-stone-600 text-sm">
              © 2026 Ember & Bloom Coffee Roasters. All rights reserved.
            </p>
          </div>
        </div>
      </footer>

      {/* Modals & Panels */}
      {selectedProduct && (
        <ProductDetail
          product={selectedProduct}
          onClose={() => setSelectedProduct(null)}
        />
      )}

      {isCartOpen && (
        <Cart
          onClose={() => setIsCartOpen(false)}
          onCheckout={() => {
            setIsCartOpen(false);
            setShowCheckout(true);
          }}
        />
      )}

      {showCheckout && (
        <Checkout
          onClose={() => setShowCheckout(false)}
          onComplete={() => setShowCheckout(false)}
        />
      )}
    </div>
  );
}

export default function App() {
  return (
    <CartProvider>
      <AppContent />
    </CartProvider>
  );
}
