import { useState } from 'react';
import { X, CreditCard, CheckCircle, ArrowLeft } from 'lucide-react';
import { useCart } from '../context/CartContext';

interface CheckoutProps {
  onClose: () => void;
  onComplete: () => void;
}

export default function Checkout({ onClose, onComplete }: CheckoutProps) {
  const { items, totalPrice, clearCart } = useCart();
  const [step, setStep] = useState<'form' | 'processing' | 'success'>('form');
  const [formData, setFormData] = useState({
    email: '',
    name: '',
    address: '',
    city: '',
    zip: '',
    cardNumber: '',
    expiry: '',
    cvv: '',
  });

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    setStep('processing');
    // Simulate processing
    setTimeout(() => {
      setStep('success');
      clearCart();
    }, 2000);
  };

  const handleChange = (field: string, value: string) => {
    setFormData(prev => ({ ...prev, [field]: value }));
  };

  if (step === 'success') {
    return (
      <div className="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div className="absolute inset-0 bg-black/70 backdrop-blur-sm" />
        <div className="relative w-full max-w-md bg-stone-900 border border-stone-700 rounded-2xl p-8 text-center">
          <CheckCircle className="w-16 h-16 text-green-400 mx-auto mb-4" />
          <h2 className="text-2xl font-serif font-bold text-amber-50 mb-2">
            Order Confirmed!
          </h2>
          <p className="text-stone-400 mb-2">
            Thank you for your order. Your specialty coffee is on its way.
          </p>
          <p className="text-stone-500 text-sm mb-6">
            Order #EB-{Math.random().toString(36).substring(2, 8).toUpperCase()}
          </p>
          <button
            onClick={onComplete}
            className="px-6 py-3 bg-amber-700 hover:bg-amber-600 text-white font-medium rounded-xl transition-colors"
          >
            Continue Shopping
          </button>
        </div>
      </div>
    );
  }

  if (step === 'processing') {
    return (
      <div className="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div className="absolute inset-0 bg-black/70 backdrop-blur-sm" />
        <div className="relative w-full max-w-md bg-stone-900 border border-stone-700 rounded-2xl p-8 text-center">
          <div className="w-12 h-12 border-4 border-amber-600 border-t-transparent rounded-full animate-spin mx-auto mb-4" />
          <h2 className="text-xl font-serif font-bold text-amber-50 mb-2">
            Processing your order...
          </h2>
          <p className="text-stone-400 text-sm">
            Please wait while we confirm your payment
          </p>
        </div>
      </div>
    );
  }

  return (
    <div className="fixed inset-0 z-50 flex items-center justify-center p-4">
      <div className="absolute inset-0 bg-black/70 backdrop-blur-sm" onClick={onClose} />
      <div className="relative w-full max-w-lg max-h-[90vh] overflow-y-auto bg-stone-900 border border-stone-700 rounded-2xl shadow-2xl">
        {/* Header */}
        <div className="sticky top-0 bg-stone-900 border-b border-stone-700/50 p-5 flex items-center justify-between z-10">
          <div className="flex items-center gap-2">
            <CreditCard className="w-5 h-5 text-amber-400" />
            <h2 className="text-lg font-serif font-semibold text-amber-50">Checkout</h2>
          </div>
          <button
            onClick={onClose}
            className="w-8 h-8 flex items-center justify-center hover:bg-stone-800 rounded-full text-stone-400 transition-colors"
          >
            <X className="w-5 h-5" />
          </button>
        </div>

        <form onSubmit={handleSubmit} className="p-5 sm:p-6 space-y-6">
          {/* Contact */}
          <div>
            <h3 className="text-sm font-medium text-stone-400 uppercase tracking-wider mb-3">
              Contact Information
            </h3>
            <div className="space-y-3">
              <input
                type="email"
                placeholder="Email address"
                required
                value={formData.email}
                onChange={(e) => handleChange('email', e.target.value)}
                className="w-full px-4 py-2.5 bg-stone-800 border border-stone-700 rounded-lg text-stone-200 placeholder-stone-500 focus:outline-none focus:border-amber-600 focus:ring-1 focus:ring-amber-600 transition-colors text-sm"
              />
              <input
                type="text"
                placeholder="Full name"
                required
                value={formData.name}
                onChange={(e) => handleChange('name', e.target.value)}
                className="w-full px-4 py-2.5 bg-stone-800 border border-stone-700 rounded-lg text-stone-200 placeholder-stone-500 focus:outline-none focus:border-amber-600 focus:ring-1 focus:ring-amber-600 transition-colors text-sm"
              />
            </div>
          </div>

          {/* Shipping */}
          <div>
            <h3 className="text-sm font-medium text-stone-400 uppercase tracking-wider mb-3">
              Shipping Address
            </h3>
            <div className="space-y-3">
              <input
                type="text"
                placeholder="Street address"
                required
                value={formData.address}
                onChange={(e) => handleChange('address', e.target.value)}
                className="w-full px-4 py-2.5 bg-stone-800 border border-stone-700 rounded-lg text-stone-200 placeholder-stone-500 focus:outline-none focus:border-amber-600 focus:ring-1 focus:ring-amber-600 transition-colors text-sm"
              />
              <div className="grid grid-cols-2 gap-3">
                <input
                  type="text"
                  placeholder="City"
                  required
                  value={formData.city}
                  onChange={(e) => handleChange('city', e.target.value)}
                  className="w-full px-4 py-2.5 bg-stone-800 border border-stone-700 rounded-lg text-stone-200 placeholder-stone-500 focus:outline-none focus:border-amber-600 focus:ring-1 focus:ring-amber-600 transition-colors text-sm"
                />
                <input
                  type="text"
                  placeholder="ZIP code"
                  required
                  value={formData.zip}
                  onChange={(e) => handleChange('zip', e.target.value)}
                  className="w-full px-4 py-2.5 bg-stone-800 border border-stone-700 rounded-lg text-stone-200 placeholder-stone-500 focus:outline-none focus:border-amber-600 focus:ring-1 focus:ring-amber-600 transition-colors text-sm"
                />
              </div>
            </div>
          </div>

          {/* Payment */}
          <div>
            <h3 className="text-sm font-medium text-stone-400 uppercase tracking-wider mb-3">
              Payment Details
            </h3>
            <div className="space-y-3">
              <input
                type="text"
                placeholder="Card number"
                required
                value={formData.cardNumber}
                onChange={(e) => handleChange('cardNumber', e.target.value)}
                className="w-full px-4 py-2.5 bg-stone-800 border border-stone-700 rounded-lg text-stone-200 placeholder-stone-500 focus:outline-none focus:border-amber-600 focus:ring-1 focus:ring-amber-600 transition-colors text-sm"
              />
              <div className="grid grid-cols-2 gap-3">
                <input
                  type="text"
                  placeholder="MM/YY"
                  required
                  value={formData.expiry}
                  onChange={(e) => handleChange('expiry', e.target.value)}
                  className="w-full px-4 py-2.5 bg-stone-800 border border-stone-700 rounded-lg text-stone-200 placeholder-stone-500 focus:outline-none focus:border-amber-600 focus:ring-1 focus:ring-amber-600 transition-colors text-sm"
                />
                <input
                  type="text"
                  placeholder="CVV"
                  required
                  value={formData.cvv}
                  onChange={(e) => handleChange('cvv', e.target.value)}
                  className="w-full px-4 py-2.5 bg-stone-800 border border-stone-700 rounded-lg text-stone-200 placeholder-stone-500 focus:outline-none focus:border-amber-600 focus:ring-1 focus:ring-amber-600 transition-colors text-sm"
                />
              </div>
            </div>
          </div>

          {/* Order Summary */}
          <div className="bg-stone-800/50 border border-stone-700/50 rounded-xl p-4">
            <h3 className="text-sm font-medium text-stone-400 uppercase tracking-wider mb-3">
              Order Summary
            </h3>
            <div className="space-y-2">
              {items.map((item) => (
                <div key={item.product.id} className="flex justify-between text-sm">
                  <span className="text-stone-300">
                    {item.product.name} × {item.quantity}
                  </span>
                  <span className="text-amber-200">
                    ${(item.product.price * item.quantity).toFixed(2)}
                  </span>
                </div>
              ))}
              <div className="border-t border-stone-700/50 pt-2 mt-2 flex justify-between">
                <span className="text-amber-50 font-medium">Total</span>
                <span className="text-lg font-bold text-amber-100">
                  ${totalPrice.toFixed(2)}
                </span>
              </div>
            </div>
          </div>

          {/* Submit */}
          <div className="flex gap-3">
            <button
              type="button"
              onClick={onClose}
              className="flex items-center gap-2 px-4 py-3 bg-stone-800 hover:bg-stone-700 text-stone-300 rounded-xl transition-colors"
            >
              <ArrowLeft className="w-4 h-4" />
              Back
            </button>
            <button
              type="submit"
              className="flex-1 py-3 bg-amber-700 hover:bg-amber-600 text-white font-medium rounded-xl transition-colors"
            >
              Place Order — ${totalPrice.toFixed(2)}
            </button>
          </div>
        </form>
      </div>
    </div>
  );
}
