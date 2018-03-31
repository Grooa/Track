import PropTypes from 'prop-types';

import CommonCard from '../common/CommonCard';

const ModuleCard = ({ module, hasAccess = false }) => {
  // eslint-disable-next-line prefer-template
  const type = `${module.type}${module.number ? ' ' + module.number : ''}`;

  return <CommonCard
    type={type}
    title={module.title}
    link={module.url}
    description={module.shortDescription}
    isDisabled={!hasAccess} />;
};

ModuleCard.propTypes = {
  module: PropTypes.object.isRequired,
  hasAccess: PropTypes.bool,
};

export default ModuleCard;
