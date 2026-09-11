import { X, Minus, Plus, Trash2, ShoppingBag } from 'lucide-react';
import { useCart } from '../context/CartContext';

interface CartProps {
  onClose: () => void;
  onCheckout: () => void;
}

export default function Cart({ onClose, onCheckout }: CartProps) {
  const { items, removeFromCart, updateQuantity, totalPrice, totalItems } = useCart();

  return (
    <div className="fixed inset-0 z-50 flex justify-end">
      {/* Backdrop */}
      <div
        className="absolute inset-0 bg-black/60 backdrop-blur-sm"
        onClick={onClose}
      />

      {/* Cart Panel */}
      <div className="relative w-full max-w-md bg-stone-900 border-l border-stone-700 shadow-2xl flex flex-col h-full">
        {/* Header */}
        <div className="flex items-center justify-between p-5 border-b border-stone-700/50">
          <div className="flex items-center gap-2">
            <ShoppingBag className="w-5 h-5 text-amber-400" />
            <h2 className="text-lg font-serif font-semibold text-amber-50">
              Your Cart
            </h2>
            {totalItems > 0 && (
              <span className="px-2 py-0.5 bg-amber-700 text-white text-xs rounded-full">
                {totalItems}
              </span>
            )}
          </div>
          <button
            onClick={onClose}
            className="w-8 h-8 flex items-center justify-center hover:bg-stone-800 rounded-full text-stone-400 hover:text-stone-200 transition-colors"
          >
            <X className="w-5 h-5" />
          </button>
        </div>

        {/* Items */}
        <div className="flex-1 overflow-y-auto p-5 space-y-4">
          {items.length === 0 ? (
            <div className="flex flex-col items-center justify-center h-full text-center">
              <ShoppingBag className="w-16 h-16 text-stone-700 mb-4" />
              <p className="text-stone-400 text-lg font-serif">Your cart is empty</p>
              <p className="text-stone-500 text-sm mt-1">
                Add some delicious coffee to get started
              </p>
            </div>
          ) : (
            items.map((item) => (
              <div
                key={item.product.id}
                className="flex gap-3 bg-stone-800/50 border border-stone-700/50 rounded-xl p-3"
              >
                {/* Image */}
                <img
                  src={item.product.image}
                  alt={item.product.name}
                  className="w-16 h-16 rounded-lg object-cover flex-shrink-0"
                />

                {/* Details */}
                <div className="flex-1 min-w-0">
                  <h4 className="text-sm font-medium text-amber-50 truncate">
                    {item.product.name}
                  </h4>
                  <p className="text-xs text-stone-500 mt-0.5">{item.product.weight}</p>
                  <p className="text-sm font-semibold text-amber-300 mt-1">
                    ${(item.product.price * item.quantity).toFixed(2)}
                  </p>
                </div>

                {/* Controls */}
                <div className="flex flex-col items-end justify-between">
                  <button
                    onClick={() => removeFromCart(item.product.id)}
                    className="text-stone-500 hover:text-red-400 transition-colors"
                  >
                    <Trash2 className="w-4 h-4" />
                  </button>
                  <div className="flex items-center bg-stone-700 rounded-lg">
                    <button
                      onClick={() => updateQuantity(item.product.id, item.quantity - 1)}
                      className="p-1.5 text-stone-400 hover:text-amber-300 transition-colors"
                    >
                      <Minus className="w-3 h-3" />
                    </button>
                    <span className="px-2 text-xs text-amber-100 font-medium">
                      {item.quantity}
                    </span>
                    <button
                      onClick={() => updateQuantity(item.product.id, item.quantity + 1)}
                      className="p-1.5 text-stone-400 hover:text-amber-300 transition-colors"
                    >
                      <Plus className="w-3 h-3" />
                    </button>
                  </div>
                </div>
              </div>
            ))
          )}
        </div>

        {/* Footer */}
        {items.length > 0 && (
          <div className="p-5 border-t border-stone-700/50 space-y-4">
            <div className="flex items-center justify-between">
              <span className="text-stone-400">Subtotal</span>
              <span className="text-lg font-bold text-amber-100">
                ${totalPrice.toFixed(2)}
              </span>
            </div>
            <div className="flex items-center justify-between text-sm">
              <span className="text-stone-500">Shipping</span>
              <span className="text-green-400 font-medium">Free</span>
            </div>
            <div className="flex items-center justify-between pt-3 border-t border-stone-700/50">
              <span className="text-amber-50 font-medium">Total</span>
              <span className="text-xl font-bold text-amber-100">
                ${totalPrice.toFixed(2)}
              </span>
            </div>
            <button
              onClick={onCheckout}
              className="w-full py-3 bg-amber-700 hover:bg-amber-600 text-white font-medium rounded-xl transition-colors"
            >
              Proceed to Checkout
            </button>
          </div>
        )}
      </div>
    </div>
  );
}
