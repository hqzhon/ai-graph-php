import React from 'react';

const Badge = ({ 
  children, 
  variant = 'default', 
  size = 'md',
  dot = false,
  icon = null,
  pulse = false,
  removable = false,
  onRemove = null,
  className = '', 
  ...props 
}) => {
  const baseClasses = `
    inline-flex items-center font-medium rounded-full transition-all duration-200 
    ${pulse ? 'animate-pulse-soft' : ''}
  `;
  
  const variants = {
    default: 'bg-gray-100 text-gray-800 border border-gray-200',
    primary: 'bg-blue-100 text-blue-800 border border-blue-200',
    secondary: 'bg-gray-100 text-gray-800 border border-gray-200',
    success: 'bg-green-100 text-green-800 border border-green-200',
    warning: 'bg-yellow-100 text-yellow-800 border border-yellow-200',
    danger: 'bg-red-100 text-red-800 border border-red-200',
    info: 'bg-blue-100 text-blue-800 border border-blue-200',
    dark: 'bg-gray-800 text-white border border-gray-700',
    outline: 'border border-gray-300 text-gray-700 bg-transparent hover:bg-gray-50',
    solid: 'bg-gray-900 text-white shadow-soft',
    gradient: 'bg-gradient-to-r from-blue-500 to-gray-500 text-white shadow-soft',
    glass: 'glass-effect text-gray-900 border border-white/20',
  };
  
  const sizes = {
    xs: 'px-1.5 py-0.5 text-xs gap-1',
    sm: 'px-2 py-0.5 text-xs gap-1',
    md: 'px-2.5 py-0.5 text-sm gap-1.5',
    lg: 'px-3 py-1 text-sm gap-1.5',
    xl: 'px-4 py-1.5 text-base gap-2',
  };
  
  const iconSizes = {
    xs: 'w-2.5 h-2.5',
    sm: 'w-3 h-3',
    md: 'w-3.5 h-3.5',
    lg: 'w-4 h-4',
    xl: 'w-5 h-5',
  };
  
  const dotSizes = {
    xs: 'w-1 h-1',
    sm: 'w-1.5 h-1.5',
    md: 'w-2 h-2',
    lg: 'w-2.5 h-2.5',
    xl: 'w-3 h-3',
  };

  const handleRemove = (e) => {
    e.stopPropagation();
    if (onRemove) {
      onRemove();
    }
  };

  return (
    <span
      className={`${baseClasses} ${variants[variant]} ${sizes[size]} ${className}`}
      {...props}
    >
      {dot && (
        <span 
          className={`${dotSizes[size]} bg-current rounded-full ${pulse ? 'animate-pulse' : ''}`}
        ></span>
      )}
      
      {icon && (
        <span className={iconSizes[size]}>
          {icon}
        </span>
      )}
      
      {children}
      
      {removable && (
        <button
          onClick={handleRemove}
          className={`
            ${iconSizes[size]} ml-1 rounded-full hover:bg-black/10 
            transition-colors duration-150 flex items-center justify-center
          `}
          aria-label="Remove badge"
        >
          <svg 
            className="w-full h-full" 
            fill="none" 
            stroke="currentColor" 
            viewBox="0 0 24 24"
          >
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      )}
    </span>
  );
};

// Badge variants for common use cases
Badge.Status = ({ status, children, ...props }) => {
  const statusVariants = {
    online: { variant: 'success', dot: true },
    offline: { variant: 'default', dot: true },
    busy: { variant: 'warning', dot: true },
    away: { variant: 'warning', dot: true },
    dnd: { variant: 'danger', dot: true },
  };
  
  return (
    <Badge {...statusVariants[status]} {...props}>
      {children || status}
    </Badge>
  );
};

Badge.Count = ({ count, max = 99, variant = 'danger', ...props }) => {
  const displayCount = count > max ? `${max}+` : count;
  
  if (count === 0) return null;
  
  return (
    <Badge variant={variant} size="xs" {...props}>
      {displayCount}
    </Badge>
  );
};

Badge.New = (props) => (
  <Badge variant="danger" size="xs" pulse {...props}>
    NEW
  </Badge>
);

Badge.Beta = (props) => (
  <Badge variant="warning" size="xs" {...props}>
    BETA
  </Badge>
);

Badge.Pro = (props) => (
  <Badge variant="gradient" size="xs" {...props}>
    PRO
  </Badge>
);

export default Badge;