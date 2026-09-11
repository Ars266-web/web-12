import { Star, Plus } from 'lucide-react';
import { Product } from '../types';
import { useCart } from '../context/CartContext';

interface ProductCardProps {
  product: Product;
  onViewDetails: (product: Product) => void;
}

export default function ProductCard({ product, onViewDetails }: ProductCardProps) {
  const { addToCart } = useCart();

  const roastColors = {
    light: 'bg-amber-100 text-amber-800',
    medium: 'bg-orange-100 text-orange-800',
    dark: 'bg-stone-700 text-stone-200',
  };

  return (
    <div className="group bg-stone-800/50 border border-stone-700/50 rounded-2xl overflow-hidden hover:border-amber-700/50 transition-all duration-300 hover:shadow-xl hover:shadow-amber-900/10 flex flex-col">
      {/* Image */}
      <div
        className="relative h-52 sm:h-56 overflow-hidden cursor-pointer"
        onClick={() => onViewDetails(product)}
      >
        <img
          src={product.image}
          alt={product.name}
          className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
        />
        <div className="absolute inset-0 bg-gradient-to-t from-stone-900/60 to-transparent" />
        <div className="absolute top-3 left-3">
          <span className={`px-2.5 py-1 rounded-full text-xs font-medium ${roastColors[product.roast]}`}>
            {product.roast.charAt(0).toUpperCase() + product.roast.slice(1)} Roast
          </span>
        </div>
        <div className="absolute bottom-3 left-3 right-3">
          <p className="text-amber-200/80 text-xs font-medium tracking-wide uppercase">
            {product.origin}
          </p>
        </div>
      </div>

      {/* Content */}
      <div className="p-4 sm:p-5 flex flex-col flex-1">
        <h3
          className="text-lg font-serif font-semibold text-amber-50 mb-1 cursor-pointer hover:text-amber-300 transition-colors"
          onClick={() => onViewDetails(product)}
        >
          {product.name}
        </h3>

        {/* Rating */}
        <div className="flex items-center gap-1 mb-2">
          <Star className="w-3.5 h-3.5 fill-amber-400 text-amber-400" />
          <span className="text-sm text-amber-300 font-medium">{product.rating}</span>
        </div>

        {/* Notes */}
        <div className="flex flex-wrap gap-1.5 mb-4">
          {product.notes.slice(0, 3).map((note) => (
            <span
              key={note}
              className="px-2 py-0.5 bg-stone-700/50 text-stone-400 text-xs rounded-full"
            >
              {note}
            </span>
          ))}
        </div>

        {/* Price & Add to Cart */}
        <div className="flex items-center justify-between mt-auto pt-3 border-t border-stone-700/50">
          <div>
            <span className="text-xl font-bold text-amber-100">${product.price.toFixed(2)}</span>
            <span className="text-stone-500 text-sm ml-1">/ {product.weight}</span>
          </div>
          <button
            onClick={() => addToCart(product)}
            className="flex items-center gap-1.5 px-3 py-2 bg-amber-700 hover:bg-amber-600 text-white text-sm font-medium rounded-lg transition-colors"
          >
            <Plus className="w-4 h-4" />
            <span className="hidden sm:inline">Add</span>
          </button>
        </div>
      </div>
    </div>
  );
}
