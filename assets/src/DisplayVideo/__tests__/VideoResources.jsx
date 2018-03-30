import { render, shallow } from 'enzyme';

import VideoResources from '../VideoResources';

describe('<VideoResources />', () => {
  let resourceList;
  let className;

  beforeEach(() => {
    resourceList = [
      {
        url: 'https://test.com/test.pdf',
        label: 'Some pdf resource',
      },
      {
        url: 'https://test.com/test2.pptx',
        label: 'Some PowerPoint resource',
      },
      {
        url: 'https://test.com/testTest',
        label: 'Some Website',
      },
    ];

    className = 'test-css';
  });

  it('should render empty list properly', () => {
    const wrapper = render(<VideoResources />);

    expect(wrapper).toMatchSnapshot();
  });

  it('should render list properly', () => {
    const wrapper = render(<VideoResources resources={resourceList} />);

    expect(wrapper).toMatchSnapshot();
  });

  it('should place className on <section>', () => {
    const wrapper = shallow(<VideoResources className={className} />);

    expect(wrapper.find('section').hasClass(className)).toBeTruthy();
  });
});
