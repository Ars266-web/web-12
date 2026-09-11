import { categories } from '../data/products';

interface CategoryFilterProps {
  selectedCategory: string;
  onCategoryChange: (category: string) => void;
}

export default function CategoryFilter({ selectedCategory, onCategoryChange }: CategoryFilterProps) {
  return (
    <div className="flex flex-wrap gap-2 sm:gap-3">
      {categories.map((cat) => (
        <button
          key={cat.id}
          onClick={() => onCategoryChange(cat.id)}
          className={`px-4 py-2 rounded-full text-sm font-medium transition-all duration-200 ${
            selectedCategory === cat.id
              ? 'bg-amber-700 text-white shadow-lg shadow-amber-900/30'
              : 'bg-stone-800 text-stone-300 hover:bg-stone-700 hover:text-amber-200 border border-stone-700'
          }`}
        >
          {cat.label}
        </button>
      ))}
    </div>
  );
}
