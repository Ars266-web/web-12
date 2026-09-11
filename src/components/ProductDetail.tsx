import { X, Star, Plus, Minus, ShoppingBag } from 'lucide-react';
import { Product } from '../types';
import { useCart } from '../context/CartContext';
import { useState } from 'react';

interface ProductDetailProps {
  product: Product;
  onClose: () => void;
}

export default function ProductDetail({ product, onClose }: ProductDetailProps) {
  const { addToCart } = useCart();
  const [quantity, setQuantity] = useState(1);
  const [added, setAdded] = useState(false);

  const handleAdd = () => {
    for (let i = 0; i < quantity; i++) {
      addToCart(product);
    }
    setAdded(true);
    setTimeout(() => setAdded(false), 1500);
  };

  const roastLabel = {
    light: 'Light Roast',
    medium: 'Medium Roast',
    dark: 'Dark Roast',
  };

  return (
    <div className="fixed inset-0 z-50 flex items-center justify-center p-4">
      {/* Backdrop */}
      <div
        className="absolute inset-0 bg-black/70 backdrop-blur-sm"
        onClick={onClose}
      />

      {/* Modal */}
      <div className="relative w-full max-w-2xl max-h-[90vh] overflow-y-auto bg-stone-900 border border-stone-700 rounded-2xl shadow-2xl">
        {/* Close button */}
        <button
          onClick={onClose}
          className="absolute top-4 right-4 z-10 w-8 h-8 flex items-center justify-center bg-stone-800/80 hover:bg-stone-700 rounded-full text-stone-300 transition-colors"
        >
          <X className="w-4 h-4" />
        </button>

        {/* Image */}
        <div className="relative h-64 sm:h-80 overflow-hidden rounded-t-2xl">
          <img
            src={product.image}
            alt={product.name}
            className="w-full h-full object-cover"
          />
          <div className="absolute inset-0 bg-gradient-to-t from-stone-900 via-transparent to-transparent" />
        </div>

        {/* Content */}
        <div className="p-6 sm:p-8 -mt-8 relative">
          {/* Origin & Category */}
          <div className="flex items-center gap-3 mb-3">
            <span className="px-3 py-1 bg-amber-900/40 text-amber-300 text-xs font-medium rounded-full border border-amber-800/30">
              {product.origin}
            </span>
            <span className="px-3 py-1 bg-stone-800 text-stone-400 text-xs font-medium rounded-full">
              {roastLabel[product.roast]}
            </span>
          </div>

          {/* Name */}
          <h2 className="text-2xl sm:text-3xl font-serif font-bold text-amber-50 mb-2">
            {product.name}
          </h2>

          {/* Rating */}
          <div className="flex items-center gap-2 mb-4">
            <div className="flex items-center gap-0.5">
              {[...Array(5)].map((_, i) => (
                <Star
                  key={i}
                  className={`w-4 h-4 ${
                    i < Math.floor(product.rating)
                      ? 'fill-amber-400 text-amber-400'
                      : 'fill-stone-700 text-stone-700'
                  }`}
                />
              ))}
            </div>
            <span className="text-sm text-amber-300">{product.rating} / 5.0</span>
          </div>

          {/* Description */}
          <p className="text-stone-300 leading-relaxed mb-6">
            {product.description}
          </p>

          {/* Tasting Notes */}
          <div className="mb-6">
            <h4 className="text-sm font-medium text-stone-400 uppercase tracking-wider mb-2">
              Tasting Notes
            </h4>
            <div className="flex flex-wrap gap-2">
              {product.notes.map((note) => (
                <span
                  key={note}
                  className="px-3 py-1.5 bg-amber-900/20 text-amber-200 text-sm rounded-full border border-amber-800/30"
                >
                  {note}
                </span>
              ))}
            </div>
          </div>

          {/* Price & Add to Cart */}
          <div className="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 pt-6 border-t border-stone-700/50">
            <div>
              <span className="text-3xl font-bold text-amber-100">
                ${product.price.toFixed(2)}
              </span>
              <span className="text-stone-500 ml-2">/ {product.weight}</span>
            </div>

            <div className="flex items-center gap-3">
              {/* Quantity */}
              <div className="flex items-center bg-stone-800 rounded-lg border border-stone-700">
                <button
                  onClick={() => setQuantity(Math.max(1, quantity - 1))}
                  className="p-2 text-stone-400 hover:text-amber-300 transition-colors"
                >
                  <Minus className="w-4 h-4" />
                </button>
                <span className="px-3 text-amber-100 font-medium min-w-[2rem] text-center">
                  {quantity}
                </span>
                <button
                  onClick={() => setQuantity(quantity + 1)}
                  className="p-2 text-stone-400 hover:text-amber-300 transition-colors"
                >
                  <Plus className="w-4 h-4" />
                </button>
              </div>

              {/* Add Button */}
              <button
                onClick={handleAdd}
                className={`flex items-center gap-2 px-5 py-2.5 rounded-lg font-medium transition-all ${
                  added
                    ? 'bg-green-700 text-white'
                    : 'bg-amber-700 hover:bg-amber-600 text-white'
                }`}
              >
                <ShoppingBag className="w-4 h-4" />
                {added ? 'Added!' : 'Add to Cart'}
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}
