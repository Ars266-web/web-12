import { ShoppingCart, Coffee, Search } from 'lucide-react';
import { useCart } from '../context/CartContext';

interface HeaderProps {
  searchQuery: string;
  onSearchChange: (query: string) => void;
  onCartClick: () => void;
}

export default function Header({ searchQuery, onSearchChange, onCartClick }: HeaderProps) {
  const { totalItems } = useCart();

  return (
    <header className="sticky top-0 z-40 bg-stone-900/95 backdrop-blur-md border-b border-amber-900/30">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="flex items-center justify-between h-16 sm:h-20">
          {/* Logo */}
          <div className="flex items-center gap-2">
            <Coffee className="w-7 h-7 text-amber-400" />
            <div>
              <h1 className="text-lg sm:text-xl font-serif font-bold text-amber-50 tracking-tight">
                Ember & Bloom
              </h1>
              <p className="hidden sm:block text-[10px] text-amber-400/70 tracking-widest uppercase">
                Specialty Coffee Roasters
              </p>
            </div>
          </div>

          {/* Search */}
          <div className="hidden md:flex flex-1 max-w-md mx-8">
            <div className="relative w-full">
              <Search className="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-stone-400" />
              <input
                type="text"
                placeholder="Search coffees..."
                value={searchQuery}
                onChange={(e) => onSearchChange(e.target.value)}
                className="w-full pl-10 pr-4 py-2 bg-stone-800 border border-stone-700 rounded-full text-sm text-stone-200 placeholder-stone-500 focus:outline-none focus:border-amber-600 focus:ring-1 focus:ring-amber-600 transition-colors"
              />
            </div>
          </div>

          {/* Cart Button */}
          <button
            onClick={onCartClick}
            className="relative flex items-center gap-2 px-4 py-2 bg-amber-700 hover:bg-amber-600 text-white rounded-full transition-colors"
          >
            <ShoppingCart className="w-5 h-5" />
            <span className="hidden sm:inline text-sm font-medium">Cart</span>
            {totalItems > 0 && (
              <span className="absolute -top-1 -right-1 w-5 h-5 bg-amber-400 text-stone-900 text-xs font-bold rounded-full flex items-center justify-center">
                {totalItems}
              </span>
            )}
          </button>
        </div>

        {/* Mobile Search */}
        <div className="md:hidden pb-3">
          <div className="relative">
            <Search className="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-stone-400" />
            <input
              type="text"
              placeholder="Search coffees..."
              value={searchQuery}
              onChange={(e) => onSearchChange(e.target.value)}
              className="w-full pl-10 pr-4 py-2 bg-stone-800 border border-stone-700 rounded-full text-sm text-stone-200 placeholder-stone-500 focus:outline-none focus:border-amber-600 focus:ring-1 focus:ring-amber-600 transition-colors"
            />
          </div>
        </div>
      </div>
    </header>
  );
}
