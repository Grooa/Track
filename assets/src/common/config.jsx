
// eslint-disable-next-line import/prefer-default-export
export function fetchConfigProps() {
  // eslint-disable-next-line no-undef
  if (!ip) {
    throw new ReferenceError('Cannot find ImpressPages properties (window.ip)');
  }

  // eslint-disable-next-line no-undef
  return ip;
}
