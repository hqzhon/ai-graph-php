import React from 'react';

const Button = ({ 
  children, 
  variant = 'primary', 
  size = 'md', 
  disabled = false, 
  loading = false, 
  icon = null,
  iconPosition = 'left',
  fullWidth = false,
  className = '', 
  ...props 
}) => {
  const baseClasses = `
    inline-flex items-center justify-center font-medium rounded-xl 
    transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 
    disabled:opacity-50 disabled:cursor-not-allowed relative overflow-hidden
    ${fullWidth ? 'w-full' : ''}
  `;
  
  const variants = {
    primary: `
      bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 
      text-white shadow-soft hover:shadow-medium focus:ring-blue-500 button-hover
    `,
    secondary: `
      bg-gray-100 hover:bg-gray-200 text-gray-900 
      focus:ring-gray-500 button-hover border border-gray-200
    `,
    success: `
      bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 
      text-white shadow-soft hover:shadow-medium focus:ring-green-500 button-hover
    `,
    warning: `
      bg-gradient-to-r from-yellow-600 to-yellow-700 hover:from-yellow-700 hover:to-yellow-800 
      text-white shadow-soft hover:shadow-medium focus:ring-yellow-500 button-hover
    `,
    danger: `
      bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 
      text-white shadow-soft hover:shadow-medium focus:ring-red-500 button-hover
    `,
    outline: `
      border-2 border-blue-600 text-blue-600 hover:bg-blue-50 
      focus:ring-blue-500 button-hover hover:border-blue-700 hover:text-blue-700
    `,
    ghost: `
      text-gray-700 hover:bg-gray-100 focus:ring-gray-500 
      button-hover hover:text-gray-900
    `,
    glass: `
      glass-effect text-gray-900 hover:bg-white/90 
      focus:ring-blue-500 button-hover backdrop-blur-md
    `,
  };
  
  const sizes = {
    xs: 'px-2 py-1 sm:px-2.5 sm:py-1.5 text-xs gap-1',
    sm: 'px-2.5 py-1.5 sm:px-3 sm:py-2 text-xs sm:text-sm gap-1 sm:gap-1.5',
    md: 'px-3 py-2 sm:px-4 sm:py-2.5 text-sm gap-1.5 sm:gap-2',
    lg: 'px-4 py-2.5 sm:px-6 sm:py-3 text-sm sm:text-base gap-2',
    xl: 'px-6 py-3 sm:px-8 sm:py-4 text-base sm:text-lg gap-2 sm:gap-2.5',
  };
  
  const iconSizes = {
    xs: 'w-3 h-3',
    sm: 'w-4 h-4',
    md: 'w-4 h-4',
    lg: 'w-5 h-5',
    xl: 'w-6 h-6',
  };
  
  const LoadingSpinner = () => (
    <svg 
      className={`animate-spin ${iconSizes[size]} ${iconPosition === 'right' ? 'order-2' : ''}`} 
      xmlns="http://www.w3.org/2000/svg" 
      fill="none" 
      viewBox="0 0 24 24"
    >
      <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4"></circle>
      <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
    </svg>
  );

  const IconComponent = () => {
    if (!icon) return null;
    return (
      <span className={`${iconSizes[size]} ${iconPosition === 'right' ? 'order-2' : ''}`}>
        {icon}
      </span>
    );
  };

  return (
    <button
      className={`${baseClasses} ${variants[variant]} ${sizes[size]} ${className}`}
      disabled={disabled || loading}
      {...props}
    >
      {loading ? <LoadingSpinner /> : <IconComponent />}
      <span className={loading && icon ? 'opacity-0' : ''}>{children}</span>
    </button>
  );
};

// Button variants for common use cases
Button.Primary = (props) => <Button variant="primary" {...props} />;
Button.Secondary = (props) => <Button variant="secondary" {...props} />;
Button.Success = (props) => <Button variant="success" {...props} />;
Button.Warning = (props) => <Button variant="warning" {...props} />;
Button.Danger = (props) => <Button variant="danger" {...props} />;
Button.Outline = (props) => <Button variant="outline" {...props} />;
Button.Ghost = (props) => <Button variant="ghost" {...props} />;
Button.Glass = (props) => <Button variant="glass" {...props} />;

export default Button;